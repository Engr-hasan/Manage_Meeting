/* 
 * Author - Harshen Pandey
 * Version - 1.0.9
 * Release - 18th March 2017
 * Copyright (c) 2017 - 2020 Harshen Pandey
 */
/* jquery.countdownTimer.js*/

(function ($) {

    $.fn.countdowntimer = function (options) {
        return this.each(function () {
            countdown($(this), options);
        });
    };

    //Definition of private function countdown.
    function countdown($this, options) {
        var opts = $.extend({}, $.fn.countdowntimer.defaults, options);
        var $this = $this;
        $this.addClass("style");
        var size = "";
        var borderColor = "";
        var fontColor = "";
        var backgroundColor = "";
        var regexpMatchFormat = "";
        var regexpReplaceWith = "";
        size = opts.size;
        borderColor = opts.borderColor;
        fontColor = opts.fontColor;
        backgroundColor = opts.backgroundColor;

        if (options.regexpMatchFormat != undefined && options.regexpReplaceWith != undefined && options.timeSeparator == undefined) {
            window['regexpMatchFormat_' + $this.attr('id')] = options.regexpMatchFormat;
            window['regexpReplaceWith_' + $this.attr('id')] = options.regexpReplaceWith;
        }

        if(options.beforeExpiryTime != undefined) {
			var expTime = opts.beforeExpiryTime.split(":");
			if(expTime[0] != "00") {
				window['beforeExpiryDays_' + $this.attr('id')] = expTime[0];
			}
			if(expTime[1] != "00") { 
				window['beforeExpiryHours_' + $this.attr('id')] = expTime[1];
			}
			if(expTime[2] != "00") {
				window['beforeExpiryMinutes_' + $this.attr('id')] = expTime[2];
			}
			if(expTime[3] != "00") { 
				window['beforeExpirySeconds_' + $this.attr('id')] = expTime[3];
			}
		}

        if (options.borderColor != undefined || options.fontColor != undefined || options.backgroundColor != undefined) {
            var customStyle = {
                "background": backgroundColor,
                "color": fontColor,
                "border-color": borderColor
            }
            $this.css(customStyle);
        } else {
            $this.addClass("colorDefinition");
        }

        if (options.size != undefined) {
            switch (size) {
                case "xl" :
                    $this.addClass("size_xl");
                    break;
                case "lg" :
                    $this.addClass("size_lg");
                    break;
                case "md" :
                    $this.addClass("size_md");
                    break;
                case "sm" :
                    $this.addClass("size_sm");
                    break;
                case "xs" :
                    $this.addClass("size_xs");
                    break;
            }
        } else if (size == "sm") {
            $this.addClass("size_sm");
        }

        if (options.startDate == undefined && options.dateAndTime == undefined && options.currentTime == undefined && (options.hours != undefined || options.minutes != undefined || options.seconds != undefined)) {

            if (options.hours != undefined && options.minutes == undefined && options.seconds == undefined) {
                hours_H = "";
                minutes_H = "";
                seconds_H = "";
                timer_H = "";
                window['hours_H' + $this.attr('id')] = opts.hours;
                window['minutes_H' + $this.attr('id')] = opts.minutes;
                window['seconds_H' + $this.attr('id')] = opts.seconds;
                if (options.pauseButton != undefined) {
                    pauseTimer($this, "H", opts, onlyHours);
                }
                if (options.stopButton != undefined) {
                    stopTimer($this, "H", opts, onlyHours);
                }
                onlyHours($this, opts);
                window['timer_H' + $this.attr('id')] = setInterval(function () {
                    onlyHours($this, opts)
                }, opts.tickInterval * 1000);
            } else if (options.hours == undefined && options.minutes != undefined && options.seconds == undefined) {
                hours_M = "";
                minutes_M = "";
                seconds_M = "";
                timer_M = "";
                window['hours_M' + $this.attr('id')] = opts.hours;
                window['minutes_M' + $this.attr('id')] = opts.minutes;
                window['seconds_M' + $this.attr('id')] = opts.seconds;
                if (options.pauseButton != undefined) {
                    pauseTimer($this, "M", opts, onlyMinutes);
                }
                if (options.stopButton != undefined) {
                    stopTimer($this, "M", opts, onlyMinutes);
                }
                onlyMinutes($this, opts);
                window['timer_M' + $this.attr('id')] = setInterval(function () {
                    onlyMinutes($this, opts)
                }, opts.tickInterval * 1000);
            } else if (options.hours == undefined && options.minutes == undefined && options.seconds != undefined) {
                hours_S = "";
                minutes_S = "";
                seconds_S = "";
                timer_S = "";
                window['hours_S' + $this.attr('id')] = opts.hours;
                window['minutes_S' + $this.attr('id')] = opts.minutes;
                window['seconds_S' + $this.attr('id')] = opts.seconds;
                if (options.pauseButton != undefined) {
                    pauseTimer($this, "S", opts, onlySeconds);
                }
                if (options.stopButton != undefined) {
                    stopTimer($this, "S", opts, onlySeconds);
                }
                onlySeconds($this, opts);
                window['timer_S' + $this.attr('id')] = setInterval(function () {
                    onlySeconds($this, opts)
                }, opts.tickInterval * 1000);
            } else if (options.hours != undefined && options.minutes != undefined && options.seconds == undefined) {
                hours_HM = "";
                minutes_HM = "";
                seconds_HM = "";
                timer_HM = "";
                window['hours_HM' + $this.attr('id')] = opts.hours;
                window['minutes_HM' + $this.attr('id')] = opts.minutes;
                window['seconds_HM' + $this.attr('id')] = opts.seconds;
                if (options.pauseButton != undefined) {
                    pauseTimer($this, "HM", opts, hoursMinutes);
                }
                if (options.stopButton != undefined) {
                    stopTimer($this, "HM", opts, hoursMinutes);
                }
                hoursMinutes($this, opts);
                window['timer_HM' + $this.attr('id')] = setInterval(function () {
                    hoursMinutes($this, opts)
                }, opts.tickInterval * 1000);
            } else if (options.hours == undefined && options.minutes != undefined && options.seconds != undefined) {
                hours_MS = "";
                minutes_MS = "";
                seconds_MS = "";
                timer_MS = "";
                window['hours_MS' + $this.attr('id')] = opts.hours;
                window['minutes_MS' + $this.attr('id')] = opts.minutes;
                window['seconds_MS' + $this.attr('id')] = opts.seconds;
                if (options.pauseButton != undefined) {
                    pauseTimer($this, "MS", opts, minutesSeconds);
                }
                if (options.stopButton != undefined) {
                    stopTimer($this, "MS", opts, minutesSeconds);
                }
                minutesSeconds($this, opts);
                window['timer_MS' + $this.attr('id')] = setInterval(function () {
                    minutesSeconds($this, opts)
                }, opts.tickInterval * 1000);
            } else if (options.hours != undefined && options.minutes == undefined && options.seconds != undefined) {
                hours_HS = "";
                minutes_HS = "";
                seconds_HS = "";
                timer_HS = "";
                window['hours_HS' + $this.attr('id')] = opts.hours;
                window['minutes_HS' + $this.attr('id')] = opts.minutes;
                window['seconds_HS' + $this.attr('id')] = opts.seconds;
                if (options.pauseButton != undefined) {
                    pauseTimer($this, "HS", opts, hoursSeconds);
                }
                if (options.stopButton != undefined) {
                    stopTimer($this, "HS", opts, hoursSeconds);
                }
                hoursSeconds($this, opts);
                window['timer_HS' + $this.attr('id')] = setInterval(function () {
                    hoursSeconds($this, opts)
                }, opts.tickInterval * 1000);
            } else if (options.hours != undefined && options.minutes != undefined && options.seconds != undefined) {
                hours_HMS = "";
                minutes_HMS = "";
                seconds_HMS = "";
                timer_HMS = "";
                window['hours_HMS' + $this.attr('id')] = opts.hours;
                window['minutes_HMS' + $this.attr('id')] = opts.minutes;
                window['seconds_HMS' + $this.attr('id')] = opts.seconds;
                if (options.pauseButton != undefined) {
                    pauseTimer($this, "HMS", opts, hoursMinutesSeconds);
                }
                if (options.stopButton != undefined) {
                    stopTimer($this, "HMS", opts, hoursMinutesSeconds);
                }
                hoursMinutesSeconds($this, opts);
                window['timer_HMS' + $this.attr('id')] = setInterval(function () {
                    hoursMinutesSeconds($this, opts)
                }, opts.tickInterval * 1000);
            }

        } else if (options.startDate != undefined && options.dateAndTime != undefined && options.currentTime == undefined) {
            startDate = "";
            endDate = "";
            timer_startDate = "";
            window['startDate' + $this.attr('id')] = new Date(opts.startDate);
            window['endDate' + $this.attr('id')] = new Date(opts.dateAndTime);
            var type = "withStart";
            givenDate($this, opts, type);
            window['timer_startDate' + $this.attr('id')] = setInterval(function () {
                givenDate($this, opts, type)
            }, opts.tickInterval * 1000);
        } else if (options.startDate == undefined && options.dateAndTime != undefined && options.currentTime == undefined) {
            startTime = "";
            dateTime = "";
            timer_givenDate = "";
            var hour = opts.startDate.getHours() < 10 ? '0' + opts.startDate.getHours() : opts.startDate.getHours();
            var minutes = opts.startDate.getMinutes() < 10 ? '0' + opts.startDate.getMinutes() : opts.startDate.getMinutes();
            var seconds = opts.startDate.getSeconds() < 10 ? '0' + opts.startDate.getSeconds() : opts.startDate.getSeconds();
            var month = (opts.startDate.getMonth() + 1) < 10 ? '0' + (opts.startDate.getMonth() + 1) : (opts.startDate.getMonth() + 1);
            var date = opts.startDate.getDate() < 10 ? '0' + opts.startDate.getDate() : opts.startDate.getDate();
            var year = opts.startDate.getFullYear();
            window['startTime' + $this.attr('id')] = new Date(year + '/' + month + '/' + date + ' ' + hour + ':' + minutes + ':' + seconds);
            window['dateTime' + $this.attr('id')] = new Date(opts.dateAndTime);
            var type = "withnoStart";
            givenDate($this, opts, type);
            window['timer_givenDate' + $this.attr('id')] = setInterval(function () {
                givenDate($this, opts, type)
            }, opts.tickInterval * 1000);
        } else if (options.currentTime != undefined) {
            currentTime = "";
            timer_currentDate = "";
            window['currentTime' + $this.attr('id')] = opts.currentTime;
            currentDate($this, opts);
            window['timer_currentDate' + $this.attr('id')] = setInterval(function () {
                currentDate($this, opts)
            }, opts.tickInterval * 1000);
        } else {
            countSeconds = "";
            timer_secondsTimer = "";
            window['countSeconds' + $this.attr('id')] = opts.seconds;
            window['timer_secondsTimer' + $this.attr('id')] = setInterval(function () {
                secondsTimer($this)
            }, 1000);
        }
    }
    ;

    

    //Function for hours and minutes are set when invoking plugin.
    function hoursMinutes($this, opts) {
        var id = $this.attr('id');
        if (window['minutes_HM' + id] == opts.minutes && window['hours_HM' + id] == opts.hours) {
            if (window['hours_HM' + id].toString().length < 2) {
                window['hours_HM' + id] = "0" + window['hours_HM' + id];
            }
            if (window['minutes_HM' + id].toString().length < 2) {
                window['minutes_HM' + id] = "0" + window['minutes_HM' + id];
            }
            html($this, window['hours_HM' + id] + opts.timeSeparator + window['minutes_HM' + id] + opts.timeSeparator + "00");
			if((typeof window['beforeExpiryHours_' + id] !== 'undefined' && window['beforeExpiryHours_' + id] == window['hours_HM' + id]) && (typeof window['beforeExpiryMinutes_' + id] !== 'undefined' && window['beforeExpiryMinutes_' + id] == window['minutes_HM' + id])) {
				beforeExpiryTime($this, opts);
			}
            if (window['hours_HM' + id] != 0 && window['minutes_HM' + id] == 0) {
                window['hours_HM' + id]--;
                window['minutes_HM' + id] = 59;
                window['seconds_HM' + id] = 60 - opts.tickInterval;
            } else if (window['hours_HM' + id] == 0 && window['minutes_HM' + id] != 0) {
                window['seconds_HM' + id] = 60 - opts.tickInterval;
                window['minutes_HM' + id]--;
            } else {
                window['seconds_HM' + id] = 60 - opts.tickInterval;
                window['minutes_HM' + id]--;
            }
            if (window['hours_HM' + id] == 0 && window['minutes_HM' + id] == 0 && window['seconds_HM' + id] == 60)
            {
                delete window['hours_HM' + id];
                delete window['minutes_HM' + id];
                delete window['seconds_HM' + id];
                clearInterval(window['timer_HM' + id]);
                timeUp($this, opts);
            }
        } else {
            if (window['hours_HM' + id].toString().length < 2) {
                window['hours_HM' + id] = "0" + window['hours_HM' + id];
            }
            if (window['minutes_HM' + id].toString().length < 2) {
                window['minutes_HM' + id] = "0" + window['minutes_HM' + id];
            }
            if (window['seconds_HM' + id].toString().length < 2) {
                window['seconds_HM' + id] = "0" + window['seconds_HM' + id];
            }
            html($this, window['hours_HM' + id] + opts.timeSeparator + window['minutes_HM' + id] + opts.timeSeparator + window['seconds_HM' + id]);
			if(((typeof window['beforeExpiryHours_' + id] !== 'undefined' && window['beforeExpiryHours_' + id] == window['hours_HM' + id]) && (typeof window['beforeExpiryMinutes_' + id] !== 'undefined' && window['beforeExpiryMinutes_' + id] == window['minutes_HM' + id]) && (typeof window['beforeExpirySeconds_' + id] !== 'undefined' && window['beforeExpirySeconds_' + id] == window['seconds_HM' + id])) || ((typeof window['beforeExpiryHours_' + id] !== 'undefined' && window['beforeExpiryHours_' + id] == window['hours_HM' + id]) && (typeof window['beforeExpiryMinutes_' + id] !== 'undefined' && window['beforeExpiryMinutes_' + id] == window['minutes_HM' + id]) && (typeof window['beforeExpirySeconds_' + id] === 'undefined' && window['seconds_HM' + id] == "00")) || ((typeof window['beforeExpiryHours_' + id] === 'undefined' && window['hours_HM' + id] == "00") && (typeof window['beforeExpiryMinutes_' + id] !== 'undefined' && window['beforeExpiryMinutes_' + id] == window['minutes_HM' + id]) && (typeof window['beforeExpirySeconds_' + id] !== 'undefined' && window['beforeExpirySeconds_' + id] == window['seconds_HM' + id])) || ((typeof window['beforeExpiryHours_' + id] !== 'undefined' && window['beforeExpiryHours_' + id] == window['hours_HM' + id]) && (typeof window['beforeExpiryMinutes_' + id] === 'undefined' && window['minutes_HM' + id] == "00") && (typeof window['beforeExpirySeconds_' + id] !== 'undefined' && window['beforeExpirySeconds_' + id] == window['seconds_HM' + id])) || ((typeof window['beforeExpiryHours_' + id] !== 'undefined' && window['beforeExpiryHours_' + id] == window['hours_HM' + id]) && (typeof window['beforeExpiryMinutes_' + id] === 'undefined' && window['minutes_HM' + id] == "00") && (typeof window['beforeExpirySeconds_' + id] === 'undefined' && window['seconds_HM' + id] == "00")) || ((typeof window['beforeExpiryHours_' + id] === 'undefined' && window['hours_HM' + id] == "00") && (typeof window['beforeExpiryMinutes_' + id] !== 'undefined' && window['beforeExpiryMinutes_' + id] == window['minutes_HM' + id]) && (typeof window['beforeExpirySeconds_' + id] === 'undefined' && window['seconds_HM' + id] == "00")) || ((typeof window['beforeExpiryHours_' + id] === 'undefined' && window['hours_HM' + id] == "00") && (typeof window['beforeExpiryMinutes_' + id] === 'undefined' && window['minutes_HM' + id] == "00") && (typeof window['beforeExpirySeconds_' + id] !== 'undefined' && window['beforeExpirySeconds_' + id] == window['seconds_HM' + id]))) {
				beforeExpiryTime($this, opts);
			}
            window['seconds_HM' + id] -= opts.tickInterval;
            if (window['minutes_HM' + id] != 0 && window['seconds_HM' + id] < 0) {
                window['minutes_HM' + id]--;
                window['seconds_HM' + id] = 60 - opts.tickInterval;
            }
            if (window['minutes_HM' + id] == 0 && window['seconds_HM' + id] < 0 && window['hours_HM' + id] != 0)
            {
                window['hours_HM' + id]--;
                window['minutes_HM' + id] = 59;
                window['seconds_HM' + id] = 60 - opts.tickInterval;
            }
            if (window['minutes_HM' + id] == 0 && window['seconds_HM' + id] < 0 && window['hours_HM' + id] == 0)
            {
                delete window['hours_HM' + id];
                delete window['minutes_HM' + id];
                delete window['seconds_HM' + id];
                clearInterval(window['timer_HM' + id]);
                timeUp($this, opts);
            }
        }
        id = null;
    }

    //Function for displaying the timer.
    function html($this, content) {
        var processedContent = content;
        if (typeof window['regexpMatchFormat_' + $this.attr('id')] !== 'undefined' &&
                typeof window['regexpReplaceWith_' + $this.attr('id')] !== 'undefined') {
            var regexp = new RegExp(window['regexpMatchFormat_' + $this.attr('id')]);
            processedContent = content.replace(regexp,
                    window['regexpReplaceWith_' + $this.attr('id')]);
        }
        $this.html(processedContent);
    }

    //Giving default value for options.
    $.fn.countdowntimer.defaults = {
        hours: 0,
        minutes: 0,
        seconds: 60,
        startDate: new Date(),
        dateAndTime: new Date("0000/00/00 00:00:00"),
        currentTime: false,
        size: "sm",
        borderColor: "#F0068E",
        fontColor: "#FFFFFF",
        backgroundColor: "#000000",
        timeSeparator: ":",
        tickInterval: 1,
        timeUp: null,
        expiryUrl: null,
        regexpMatchFormat: null,
        regexpReplaceWith: null,
        pauseButton: null,
        stopButton: null,
	beforeExpiryTime: null,
	beforeExpiryTimeFunction: null
    };

}(jQuery));
