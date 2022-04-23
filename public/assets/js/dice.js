$(document).ready(function() {
	// initSlider('.modal_dice');
	// var fairCarousel = initFairSlider('.fair-slider');
	$("#slider").ionRangeSlider({
		skin: "round",
		min: 0,
        max: 100,
		from: 50,
		step: 0.1,
		from_min: min_chance,
		from_max: max_chance,
		grid: true,
		grid_margin: true,
		force_edges: true,
		hide_min_max: true,
		onChange: function(data){
			$('.irs-single').fadeIn();
			calcBy('slider', data.from);
		},
		onFinish: function(data){
			$('.irs-single').fadeOut(200);
		},
		prettify: function(val) {
			return val.toFixed(2);
		}
	});
	var slider = $("#slider").data("ionRangeSlider");
	var slider_min = slider.options.from_min;
	var slider_max = slider.options.from_max;
	var current_coeff = 2;
	var current_chance = 50;
	var less = true;

	function roundTo(num, digits){
		return Math.round((num + Number.EPSILON) * Math.pow(10, digits)) / Math.pow(10, digits);
	}

	function calcBy(what, val){
		if (what === 'chance') {
			var _coeff = roundTo(((100 - comission) / val), 2);
			var side_val = roundTo(less ? val : 100 - val, 2);
			$('.dice-input-coeff').val(_coeff.toFixed(2));
			$('.game-area_dice-input-sfx_coef').text(_coeff.toFixed(2));
			$('.dice-input-side').val(side_val.toFixed(2));
			slider.update({
				from: side_val.toFixed(2)
			});
		}

		if (what === 'coeff') {
			var _chance = roundTo(((100 - comission) / val), 2);
			if (_chance < min_chance) _chance = min_chance;
			if (_chance > max_chance) _chance = max_chance;
			var side_val = roundTo(less ? _chance : 100 - _chance, 2);

			$('.dice-input-chance').val(_chance.toFixed(2));
			$('.game-area_dice-input-sfx_chance').text(_chance.toFixed(2));
			$('.dice-input-side').val(side_val.toFixed(2));
			slider.update({
				from: side_val.toFixed(2)
			});
		}

		if (what === 'slider') {
			var _chance = roundTo(less ? val : 100 - val, 2);
			var _coeff = roundTo(((100 - comission) / _chance), 2);
			$('.dice-input-side').val(val.toFixed(2));
			$('.dice-input-coeff').val(_coeff.toFixed(2));
			$('.game-area_dice-input-sfx_coef').text(_coeff.toFixed(2));
			$('.dice-input-chance').val(_chance.toFixed(2));
			$('.game-area_dice-input-sfx_chance').text(_chance.toFixed(2));
		}

		if (what === 'side') {
			less = !less;
			var side_val;
			if (less){
				$('.game-sidebar__input-label_switch').text('Меньше');
				side_val = val;
			}
			else{
				$('.game-sidebar__input-label_switch').text('Больше');
				side_val = 100 - val;
			}
			
			slider_min = 100 - slider_min;
			slider_max = 100 - slider_max;
			$('.game-area_dice-input-switch').toggleClass('rotated');
			$('.game-area-slider').toggleClass('reversed');

			$('.dice-input-side').val(side_val.toFixed(2));
			slider.update({
				from_min: Math.min(slider_min, slider_max).toFixed(2),
				from_max: Math.max(slider_min, slider_max).toFixed(2),
				from: side_val.toFixed(2)
			});
		}

		calcPW();
		current_coeff 	= parseFloat($('.dice-input-coeff').val());
		current_chance 	= parseFloat($('.dice-input-chance').val());
	}

	$('#amount').on('input', calcPW);

	function calcPW(){
		var bet = parseInt($('#amount').val()) || 0;
		var coeff = parseFloat($('.dice-input-coeff').val()) || 0;
		var result = Math.floor(bet * coeff);
		if (result < 0) result = 0;
		$('.game-area-possible-winning-val').text(result);
	}
calcBy('side', 50);
	$('.dice-input-side').on('click', function(){
		var chance = parseFloat($('.dice-input-chance').val()) || 0;
		if (isNaN(chance) || chance < min_chance || chance > max_chance) {
			return;
		}
		calcBy('side', chance);
	});

	$('.dice-input-chance').on('input', function(){
		var chance = parseFloat($(this).val()) || 0;
		$('.dice-input-chance').not(this).val($(this).val());
		if (isNaN(chance) || chance < min_chance || chance > max_chance || _feq(chance, current_chance)) {
			return;
		}
		calcBy('chance', chance);
	});

	$('.dice-input-coeff').on('input', function(){
		var _val = parseFloat($(this).val()) || 0;
		$('.dice-input-coeff').not(this).val($(this).val());
		if (isNaN(_val) || _val < 1 || _feq(_val, current_coeff)) {
			return;
		} 
		calcBy('coeff', _val);
	});

	function _feq(a, b){
		return (Math.abs(a - b) <= 0.000001);
	}

	var timerId;
	var socket = io.connect(domain + ':' + mainPort);
	socket.on('dice', function (data) {	
		var html = '<tr class="show games-table__body-tr"> \
			<td class="games-table__body-td games-table__body-td_low_padding">\
				<div class="flex-align game-container__table__player">\
					<span>' + data.username + '</span>\
						</div>\
					</td>\
			<td class="games-table__body-td games-table__money">\
				<span class="myicon-coins"></span> ' + data.sum + '\
			</td>\
			<td class="games-table__body-td">' + (data.win ? data.vip.toFixed(2) : '0.00') + 'x</td>\
			<td class="games-table__body-td">' + data.num.toFixed(2) + '</td>\
			<td class="games-table__body-td games-table__money games-table__money_' + (data.win ? 'win' : 'lost') + '">' + (data.win ? (parseInt(data.win_sum) + parseInt(data.sum)) : '0') + '\
			<span class="myicon-coins"></span></td> \
			<td class="games-table__body-td games-table__body-td__fair games-table__body-td_no_padding"><a href="/fair/' + data.hash + '" class="tooltip" title="Проверить" target="_blank"><span class="myicon-security"></span></a></td>\
		</tr>';

		$('#lastGame').prepend(html);
		if($('.show').length >= 10) $('.show:nth-child(11)').remove();
		$('.tooltip').tooltipster({
			side: 'bottom',
			theme: 'tooltipster-borderless'
		});
	});
	
	function setIndicatorPosition(pos){
		var ml = parseFloat($('.irs-grid').css('left'));
		var w = parseFloat($('.irs-grid').css('width'));
		$('.index__home__indicator__inner__number').animate({
			'margin-left': ml + 'px',
			'left': w * pos / 100 + 'px'
		}, 150);
	}
	$('.dice-create-game').click(function(e) {
		// if (!validateField('#amount')){
		// 	return;
		// }
		// if (!validateField('.dice-input-chance')){
		// 	return;
		// }
		clearTimeout(timerId);

		$('.dice-create-game').prop('disabled', true);
		var amount = parseInt($('#amount').val())
		var target = parseFloat($('.dice-input-side').val());
        var coeff = parseFloat($('.dice-input-coeff').val());
        var chance = parseFloat($('.dice-input-chance').val());
        // console.log(target);
		// if (Math.floor(coeff * amount) == amount){
		// 	$.notify({
		// 		position : 'bottom-right',
		// 		type: 'error',
		// 		message: 'Ваша ставка равна выигрышу'
		// 	});
		// 	$('.dice-create-game').prop('disabled', false);
		// 	return;
		// }
		$.ajax({
			url: "/dice/play",
			type: 'post',
			data: {
				sum: amount,
				perc: 100 - chance,
			},
			success: function(data) {
				if (data.status == 'success') {
					$('.index__home__indicator__inner').show();
					setIndicatorPosition(data.chislo);
					updateBalanceAnimated('#money_mob', data.balance);
					// displayFairDataOnModal(data.server_seed, data.client_seed, data.salt, data.current_server_seed, data.current_client_seed, fairCarousel);
					$('.index__home__indicator__inner__number__roll>span').html(data.chislo.toFixed(2));
					if(data.win == 1) {
						$('.index__home__indicator__inner__number__roll').removeClass('is-negative');
						$('.index__home__indicator__inner__number__roll').addClass('is-positive')
					} else {
						$('.index__home__indicator__inner__number__roll').addClass('is-negative');
						$('.index__home__indicator__inner__number__roll').removeClass('is-positive')
					}

					timerId = setTimeout(function() {
						$('.index__home__indicator__inner').fadeOut('fast')
					}, 5000)
				}
				else{
					// $.notify({
					// 	position : 'bottom-right',
					// 	type: 'error',
					// 	message: data.msg
					// });
				}
				$('#hash').val(data.hash);
				$('.dice-create-game').prop('disabled', false);
			},
			error : function(data) {
				$.notify({
				  position : 'bottom-right',
				  type: 'error',
				  message: 'Произошла ошибка при отправке данных'
				});
				$('.dice-create-game').prop('disabled', false);
			}
		})
	});


});



