var fs = require('fs'),
    options = {
        key: fs.readFileSync('/var/www/html/storage/bot/privkey.pem', 'utf8'),
        cert: fs.readFileSync('/var/www/html/storage/bot/fullchain.pem', 'utf8')
    },
    config          = require('./config.js'),
    app             = require('express')(),
    server          = require('https').createServer(options, app),
    io              = require('socket.io')(server),
	redis 			= require('redis'),
    redis 			= redis.createClient({
		path : '/var/run/redis/redis.sock'
	}),
	requestify 		= require('requestify'),
	double          = new (require('./double'))(io, config.domain),
	online 			= 0,
	ipsConnected	= [],
    crash           = require('./crash'),
	game = {
		room : null,
		id : 0,
		status : 0
	}

double.start();
crash.init();

io.on('connection', (socket) => {
    socket.on('withdraw', (persona) => {
        crash.withdraw(persona.hash, persona.multiplier, (res) => {
            socket.emit('withdraw', res);
        });
    });
});


function log(log) { console.log('[APP] ' + log) }

server.listen(config.port);
log('Локальный сервер запущен на порте '+config.port);

io.sockets.on('connection', function(socket) {
	var address = socket.handshake.address;
	if(!ipsConnected.hasOwnProperty(address)) {
		ipsConnected[address] = 1;
		online = online + 1;
	}
	let timer = String(giftTimer.hours).padStart(2,'0')+':'+String(giftTimer.minutes).padStart(2,'0');
	io.sockets.emit("giftTimer", timer);
	updateOnline(online);
    socket.on('disconnect', function() {
		if(ipsConnected.hasOwnProperty(address)) {
			delete ipsConnected[address];
			online = online - 1;
		}
		updateOnline(online);
	});
});

function updateOnline(usersCount) {
	io.sockets.emit('online', usersCount);
}
/* USERS ONLINE SITE END */

redis.subscribe('chat.clear');
redis.subscribe('new.msg');
redis.subscribe('del.msg');
redis.subscribe('ban.msg');
redis.subscribe('message');
redis.subscribe('bonus_win');
redis.subscribe('updateBalance');
redis.subscribe('updateBalanceAfter');
redis.subscribe('jackpot.newBet');
redis.subscribe('jackpot.timer');
redis.subscribe('roulette');
redis.subscribe('new.flip');
redis.subscribe('end.flip');
redis.subscribe('dice');
redis.subscribe('bonus');
redis.subscribe('battle.newBet');
redis.subscribe('battle.startTime');
redis.subscribe('battle.timer');
redis.subscribe('giftTimer');
redis.subscribe('battle.slider');
redis.subscribe('updateBank');
redis.subscribe('crash');

redis.on('message', function(channel, message) {
    if (channel == 'chat.clear') {
        log('[CHAT] Чат очищен!');
        io.sockets.emit('clear', message);
    }
    if (channel == 'new.msg') {
        io.sockets.emit('chat', message);
    }
	if (channel == 'del.msg') {
        io.sockets.emit('chatdel', message);
    }
	if(channel == 'ban.msg') {
        io.sockets.emit('ban_message', JSON.parse(message));
    }
	if(channel == 'jackpot.timer') {
        data = JSON.parse(message);
		if(data.room == game.room) if(game.status < 1) game.status = 1;
        JackpotStartTimer(data.room, data.min, data.sec, data.time);
        return;
    }
	if(channel == 'roulette' && JSON.parse(message).type == 'back_timer') {
        message = JSON.parse(message);
        return double.startTimer(message.timer);
    }
    if (channel == 'new.flip') {
        io.sockets.emit(channel, JSON.parse(message));
		return;
    }
    if (channel == 'end.flip') {
        io.sockets.emit(channel, JSON.parse(message));
		return;
    }
    if (channel == 'dice') {
        io.sockets.emit(channel, JSON.parse(message));
		return;
    }
    if (channel == 'bonus') {
        io.sockets.emit(channel, JSON.parse(message));
		return;
    }
	if(channel == 'updateBank') {
        io.sockets.emit('updateBank', JSON.parse(message));
    }
	if(channel == 'battle.gameBets') {
		io.sockets.emit('battle.newBet', JSON.parse(message));
    }
	if(channel == 'battle.startTime') {
        data = JSON.parse(message);
        BattleStartTimer(data.time);
        return;
    }
	if(channel == 'updateBalanceAfter') setTimeout(function() {
        io.sockets.emit('updateBalance', JSON.parse(message));
    }, 8000);
	if(channel == 'bonus_win') setTimeout(function() {
        io.sockets.emit('message', JSON.parse(message));
    }, 8000);
    if(channel == 'crash') {
		return io.sockets.emit('crash', JSON.parse(message));
	}
    if(typeof message == 'string') return io.sockets.emit(channel, JSON.parse(message));
    io.sockets.emit(channel, message);
});

function JackpotStartTimer(room, min, sec, time) {
	var preFinish = false;
	var total = time;
	var time = time;
	var timer;
	clearInterval(timer);
    timer = null;
    timer = setInterval(function() {
		time--;
        sec--;
		if(time == 3) {
			if(!preFinish) {
				preFinish = true;
				JackpotSetStatus(room, 2);
			}
		}
		if(sec == 0) {
			if(min == 0) {
				clearInterval(timer);
				timer = null;
				JackpotGetSlider(room);
				return;
			}
			min--;
			sec = 60;
		}
        io.sockets.emit('jackpot.timer', {
			room : room,
            min : min,
            sec : sec,
            time : time,
            timer : total
        });
    }, 1000);
}

const giftTimer = {
	hours: 1,
	minutes: 59
};



function gift(){
	requestify.post(config.domain+'/api/chat/gift', {
		secret: 'jopkaEnigmana'
	});
}
setInterval(function(){
	giftTimer.minutes--;
	
	if ( giftTimer.hours == 0 && giftTimer.minutes == 0 ){
		gift();
		giftTimer.hours = 59;
		giftTimer.minutes = 59;
	}else{
	if (giftTimer.minutes == 0 ){
		giftTimer.hours--;
		giftTimer.minutes = 59;
	}
}
	let timer = String(giftTimer.hours).padStart(2,'0')+':'+String(giftTimer.minutes).padStart(2,'0');
	console.log(timer);
	io.sockets.emit("giftTimer", timer);
}, 1000);

function JackpotGetSlider(room) {
    requestify.post(config.domain + '/api/jackpot/getSlider', {
        room : room
    })
    .then(function(res) {
        res = JSON.parse(res.body);
        io.sockets.emit('jackpot.slider', res);
		ngTimer(room);
    }, function(res) {
        log('[ROOM '+room+'] Ошибка в функции getSlider');
		//setTimeout(JackpotGetSlider, 1000, room);
    });
}

function ngTimer(room) {
	var ngtime = 16;
	clearInterval(ngtimer);
	var ngtimer = setInterval(function() {
		ngtime--;
		io.sockets.emit('jackpot.ngTimer', {
			room : room,
			ngtime : ngtime,
		});
		if(ngtime == 0) {
			clearInterval(ngtimer);
			JackpotNewGame(room);
		}
	}, 1000);
}

function JackpotNewGame(room) {
    requestify.post(config.domain + '/api/jackpot/newGame', {
        room : room
    })
    .then(function(res) {
        res = JSON.parse(res.body);
        io.sockets.emit('jackpot.newGame', res);
    }, function(res) {
        log('[ROOM '+room+'] Ошибка в функции newGame');
		//setTimeout(JackpotNewGame, 1000, room);
    });
}

function BattleStartTimer(time) {
	var preFinish = false;
	var BattleTimer,
		time = time + 1;
	BattleSetStatus(1);
	clearInterval(BattleTimer);
    BattleTimer = null;
    BattleTimer = setInterval(function() {
		time--;
		if(time <= 0) {
			if(!preFinish) {
				clearInterval(BattleTimer);
				BattleTimer = null;
				preFinish = true;
				BattleSetStatus(2);
				BattleStartPreTimer();
			}
		}
        io.sockets.emit('battle.timer', {
            pretime : 10,
            time : time
        });
		log('[BATTLE] Таймер: ' + time);
    }, 1000);
}

function BattleStartPreTimer() {
	var BattlePreTimer,
		pretime = 4;
	clearInterval(BattlePreTimer);
    BattlePreTimer = null;
    BattlePreTimer = setInterval(function() {
		pretime--;
		if(pretime <= 0) {
			clearInterval(BattlePreTimer);
			BattlePreTimer = null;
			BattleShowWinners();
		}
        io.sockets.emit('battle.timer', {
            pretime : pretime,
            time : 0
        });
		log('[BATTLE] Таймер до начала игры: ' + pretime);
    }, 1000);
}

function BattleShowWinners() {
    requestify.post(config.domain + '/api/battle/getSlider')
    .then(function(res) {
        res = JSON.parse(res.body);
        io.sockets.emit('battle.slider', res);
		log('[BATTLE] Показываем победителя');
		BattleSetStatus(3);
		BattleNgTimer();
    }, function(res) {
        log('[BATTLE] Ошибка в функции getSlider');
		setTimeout(BattleShowWinners, 1000);
    });
}

function BattleNgTimer() {
	var ngtime = 6;
	var battlengtimer = setInterval(function() {
		ngtime--;
		if(ngtime <= 0) {
			clearInterval(battlengtimer);
			BattleNewGame();
		}
		log('[BATTLE] Новая игра через: ' + ngtime);
	}, 1000);
}

function BattleNewGame() {
    requestify.post(config.domain + '/api/battle/newGame')
    .then(function(res) {
        res = JSON.parse(res.body);
        io.sockets.emit('battle.newGame', res);
		log('[BATTLE] Новая игра');
    }, function(res) {
        log('[BATTLE] Ошибка в функции newGame');
		setTimeout(BattleNewGame, 1000);
    });
}

// Проверка статусов
function JackpotGetStatus(room) {
	requestify.post(config.domain + '/api/jackpot/getStatus', {
        room : room
    })
	.then(function(res) {
		res = JSON.parse(res.body);
		log('[ROOM '+room+'] Current game #' + res.id)

		game = {
			room : res.room,
			id : res.id,
			status : res.status
		}

		if(res.status == 1) JackpotStartTimer(res.room, res.min, res.sec, res.time, res.timer);
		if(res.status == 2) JackpotStartTimer(res.room, res.min, res.sec, res.time, res.timer);
		if(res.status == 3) JackpotNewGame(res.room);
	}, function(res) {
		log('[ROOM '+room+'] Ошибка в функции getStatus');
		setTimeout(JackpotGetStatus, 1000, room);
	});
}

function JackpotSetStatus(room, status) {
	game.status = status;
    requestify.post(config.domain + '/api/jackpot/setStatus', {
        room : room,
		status : status
    })
    .then(function(res) {
        res = JSON.parse(res.body);
		log(res.msg);
    }, function(res) {
        log('[ROOM '+room+'] Ошибка в функции setStatus');
		setTimeout(JackpotGetStatus, 1000, room, status);
    });
}

requestify.post(config.domain + '/api/battle/getStatus')
.then(function(res) {
	res = JSON.parse(res.body);
	log('[BATTLE] Текущая игра #' + res.id)
	if(res.status == 1) BattleStartTimer(res.time);
	if(res.status == 2) BattleStartTimer(res.time);
	if(res.status == 3) BattleNewGame();
}, function(res) {
	log('[BATTLE] Ошибка в функции getStatus');
});

function BattleSetStatus(status) {
    requestify.post(config.domain + '/api/battle/setStatus', {
		status : status
    })
    .then(function(res) {
        status = JSON.parse(res.body);
		log('[BATTLE] Статус игры изменен на ' + status);
    }, function(res) {
        log('[BATTLE] Ошибка в функции setStatus');
		setTimeout(BattleSetStatus, 1000);
    });
}

function getMerchBalance() {
    requestify.post(config.domain+'/api/getMerchBalance')
    .then(function(response) {
        var balance = JSON.parse(response.body);
        log('['+balance.type+'] '+balance.msg);
        setTimeout(getMerchBalance, 600000);
    },function(response){
        log('Ошибка в функции [getMerchBalance]');
        setTimeout(getMerchBalance, 1000);
    });
}

// Фейк ставки
function fakeBetJackpot(status) {
	if(status) {
		requestify.post(config.domain + '/api/jackpot/addBetFake')
		.then(function(res) {
			res = JSON.parse(res.body);
			if(res.success) log(res.msg);
			else log(res.msg);
			setTimeout(function() {
				fakeBetJackpot(res.fake);
			}, Math.round(getRandomArbitrary(5, 17) * 1000));
		}, function(res) {
			log('[Jackpot] Ошибка при добавлении ставки!');
			log(JSON.parse(res.body));
			setTimeout(function() {
				fakeBetJackpot(1);
			}, Math.round(getRandomArbitrary(5, 17) * 1000));
		});
	} else {
		setTimeout(fakeStatus, 5000);
	}
}

function fakeBetRoulette(status) {
	if(status) {
		requestify.post(config.domain + '/api/roulette/addBetFake')
		.then(function(res) {
			res = JSON.parse(res.body);
			if(res.success) log(res.msg);
			else log(res.msg);
			setTimeout(function() {
				fakeBetRoulette(res.fake);
			}, Math.round(getRandomArbitrary(1, 8) * 1000));
		}, function(res) {
			log('[Double] Ошибка при добавлении ставки!');
			log(JSON.parse(res.body));
			setTimeout(function() {
				fakeBetRoulette(1);
			}, Math.round(getRandomArbitrary(1, 8) * 1000));
		});
	} else {
		setTimeout(fakeStatus, 5000);
	}
}

function fakeBetDice(status) {
	if(status) {
		requestify.post(config.domain + '/api/dice/addBetFake')
		.then(function(res) {
			res = JSON.parse(res.body);
			if(res.success) log(res.msg);
			else log(res.msg);
			setTimeout(function() {
				fakeBetDice(res.fake);
			}, Math.round(getRandomArbitrary(1, 3) * 1000));
		}, function(res) {
			log('[Dice] Ошибка при добавлении ставки!');
			log(JSON.parse(res.body));
			setTimeout(function() {
				fakeBetDice(1);
			}, Math.round(getRandomArbitrary(1, 3) * 1000));
		});
	} else {
		setTimeout(fakeStatus, 5000);
	}
}

function fakeStatus() {
    requestify.post(config.domain+'/api/getParam')
    .then(function(response) {
        var res = JSON.parse(response.body);
		if(res.fake) {
			fakeBetRoulette(res.fake);
			fakeBetJackpot(res.fake);
			fakeBetDice(res.fake);
		} else {
			setTimeout(fakeStatus, 5000);
		}
    },function(response){
        log('Ошибка в функции [fakeStatus]');
        setTimeout(fakeStatus, 1000);
    });
}

function getRandomArbitrary(min, max) {
    return Math.random() * (max - min) + min;
}

function getRandomInt(min, max) {
    return Math.floor(Math.random() * (max - min + 1)) + min;
}

function unBan() {
    requestify.post(config.domain+'/api/unBan')
    .then(function(res) {
        var data = JSON.parse(res.body);
        log('[UnBAN] ' + data.msg);
        setTimeout(unBan, 60000);
    },function(response){
        log('Ошибка в функции [unBan]');
        setTimeout(unBan, 1000);
    });
}

fakeStatus();
getMerchBalance();
unBan();

requestify.post(config.domain + '/api/jackpot/getRooms')
.then(function(res) {
	rooms = JSON.parse(res.body);
	rooms.forEach(function(room) {
		JackpotGetStatus(room.name);
	});
}, function(res) {
	log('[APP] Ошибка в функции getRooms');
});
