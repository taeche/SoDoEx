var JVScrolling = (function($){
    var safari = /^((?!chrome).)*safari/i.test(navigator.userAgent);
    return $.extend(function(ops){
        var
            self = arguments.callee,
            This = this,
            $this = $(this),
            $doc = $(document),
            $win = $(window),
            scrolled = true,
            boxs, hiddens = $()
        ;
        var convert = {
            'string' : function(val){ return val + '';},
            'number' : function(val){ return parseFloat(val);},
            'boolean': function(val){ return !!parseInt(val);}
        }
        $.each(ops,function(k,v){
            if(self.options[k] !== undefined){
                var type = typeof(self.options[k]);
                
                if(!convert[type]) return;
                ops[k] = convert[type](ops[k]);
            }
        });
        ops = $.extend({},self.options,ops);
        var
            timeOut,
            start = function(){
                    boxs = $(ops.selector);
                if (disabled()) {
                    return resetStyle();
                } else {
                    if(!boxs.length) return;                    
                    boxs.each(function(i){
                        var item = $(this);
                        applyStyle(item,true);
                        group = item.parents(ops.groupPoint).first();
                        if(group.length){
                            var index = (group.data('groupPointIndex') || 0) + 1;
                            group.data('groupPointIndex',index);
                            item.data('groupIndex',index);
                        }
                    });
                    $win.bind('scroll resize',scrollHandler);
                    var check = function(){
                        clearTimeout(timeOut);
                        timeOut = setTimeout(check, 50);
                        scrollCallback();
                    }
                    check();
                }
            },
            stop = function(){
                clearTimeout(timeOut);
                $win.unbind('scroll resize',scrollHandler);
            },
            show = function(box) {
                applyStyle(box);
                effect = box.data('effect') || ops.effect
                box.addClass(effect).one('animationend webkitAnimationEnd oanimationend MSAnimationEnd', function(){
                    box.removeClass(effect);
                });
            },
            applyStyle = function (box, hidden) {
                var effect = box.data('effect') || ops.effect;
                if(!self.effects[effect] && hidden) return box.css('visibility','hidden');
                var 
                    delay = (parseTime(box.css('animation-delay')) || box.data('delay') || ops.delay) || 0,
                    duration = (parseTime(box.css('animation-duration')) || box.data('duration') || ops.duration) || 0,
                    iteration = (box.data('iteration') || ops.iteration) || 1
                ;
                if(ops.groupDesc){
                    var group = box.parents(ops.groupPoint).first();
                    if(group.length){
                        delay += group.data('groupPointIndex') * ops.groupDelay;
                        delay += ops.groupDelay * (box.data('groupIndex') || 0) * -1
                    }
                }else delay += ops.groupDelay * box.data('groupIndex') || 0;
                if(self.effects[effect]){
                    effect = self.effects[effect];
                    return effect.call(box,{
                        delay: delay,
                        duration: duration,
                        iteration: iteration
                    },hidden);
                }
                return customStyle(box, duration, delay, iteration);
            },
            parseTime = function(time){
                if(/[0-9]+s/.test(time)) return parseFloat(time) * 1000;
                if(/[0-9]+ms/.test(time)) return parseFloat(time);
            },
            resetStyle = function () {
                $.each(boxs,function(){
                    this.setAttribute('style', '');
                });
            },
            customStyle = function (box, duration, delay, iteration) {
                box.css({
                    'animation-iteration-count': iteration + '',
                    'animation-duration': duration  + 'ms',
                    'animation-delay': delay + 'ms'
                }).one({
                    'animationstart webkitAnimationStart oanimationstart MSAnimationStart':function(){
                        box.css('visibility','');
                    },
                    'animationend webkitAnimationEnd oanimationend MSAnimationEnd': function(){
                        box.css('visibility','');
                    }
                });
                safari && setTimeout(function(){
                    box.css('visibility','');
                },delay + 50);
            },
            isVisible = function(box) {
                var 
                    offset = box.data('offset') || ops.offset || 0;
                    
                    var
                    viewTop = window.pageYOffset,
                    viewBottom = viewTop + window.innerHeight,
                    top = box.offset().top,
                    bottom = top + box[0].clientHeight
                ;
                viewTop += offset; viewBottom -= offset;
                
                return top <= viewBottom && bottom > viewTop || bottom >= viewTop && top < viewBottom;
            },
            supports = (function() {
                var div = document.createElement('div'),
                    vendors = 'Khtml Ms O Moz Webkit'.split(' ');

                return function(prop) {
                    var len = vendors.length;
                    if ( prop in div.style ) return true;            
                        prop = prop.replace(/^[a-z]/, function(val) {
                        return val.toUpperCase();
                    });          
                    while(len--) {
                        if ( vendors[len] + prop in div.style ) {
                            return true;
                        } 
                    }
                    return false;
                };
            })(),
            disabled = function() {return !supports('animation') || !ops.mobile && (/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows (ce|phone)|xda|xiino|android|ipad|playbook|silk/i.test(navigator.userAgent||navigator.vendor||window.opera)||/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i.test((navigator.userAgent||navigator.vendor||window.opera).substr(0,4)));},
            scrollHandler = function(){scrolled = true;},
            scrollCallback = function () {
                hiddens = (function(){
                    var clone = $();
                    hiddens.each(function(i){
                        var box = $(this);
                        if(box.is(':visible')){
                            boxs.push(this);
                            scrolled = true;
                        }else{
                            clone.push(this);
                        }
                    });
                    return clone;
                })();
                if (scrolled) {
                    scrolled = false;
                    boxs = (function(){
                        var clone = $();
                        boxs.each(function(i){
                            var box = $(this);
                            if(!box.is(':visible')){
                                hiddens.push(this);
                            }else if(isVisible(box)){
                                show(box);
                                return;
                            }else clone.push(this);
                        });
                        return clone;
                    })();
                    if (!boxs.length && !hiddens.length) {
                        return stop();
                    }
                }
            }
        ;
        
        start();
        var inter = setInterval(function(){
            scrolled = true;
        },200);
        $(window).load(function(){
            clearInterval(inter);
            scrolled = true;
        });
        $.extend(this,{
            
        });
    },{
        options:{
            selector: '.jvscrolling',
            mobile: true,
            duration: 500,
            offset: 0,
            delay: 0,
            iteration: 0,
            effect: 'ease',
            groupDelay: 0,
            groupDesc: false
        },
        effects:{
            counting: function(ops,hidden){
                var 
                    box =  this,
                    data = box.data()
                ;
                
                if(hidden){
                    
                    
                    data.countingFrom = parseFloat(box.data('from')) || 0;
                    data.countingTo = parseFloat(box.data('to')) || parseFloat(box.text()) || 0;
                    data.toFixed = parseFloat(box.data('fixed')) || 0;
                    box.text(data.countingFrom);
                }else{
                    setTimeout(function(){
                        $(data).animate({
                            countingFrom: data.countingTo
                        },{
                            duration: ops.duration,
                            step: function(i){
                                box.text(i.toFixed(data.toFixed));
                            }
                        });
                    },ops.delay);
                }
            },
            clipcircle: function(ops,hidden){
                var 
                    box =  this,
                    data = box.data()
                ;
                if(hidden){
                    var
                        max = parseFloat(box.data('max')) || 100
                    ;
                    data.countingFrom = parseFloat(box.data('from')) || 0.5;
                    data.countingFrom = 360/max * data.countingFrom;
                    data.countingTo = parseFloat(box.data('to')) || parseFloat(box.text()) || 0.5;
                    data.countingTo = 360/max * data.countingTo;
                    data.bgColor = box.css('background-color');
                    data.lineColor = box.data('color') || '#000';
                    
                    data.set = function(i){
                        if(i > 180){
                            i -= 180;
                            var css =  'linear-gradient('+i+'deg, '+data.lineColor+' 50%, transparent 50%, transparent), linear-gradient(0deg, '+data.lineColor+' 50%, transparent 50%, transparent)';
                            box.css({
                                'background-color': 'none',
                                'background-image': css
                            });
                        }else{
                            var css =  'linear-gradient('+i+'deg, '+data.bgColor+' 50%, transparent 50%, transparent), linear-gradient(0deg, '+data.lineColor+' 50%, transparent 50%, transparent)';
                            box.css({
                                'background-color': 'none',
                                'background-image': css
                            });
                        }
                        
                    }
                    data.set(data.countingFrom);
                }else{
                    setTimeout(function(){
                        $(data).animate({
                            countingFrom: data.countingTo
                        },{
                            duration: ops.duration,
                            step: data.set
                        });
                    },ops.delay);
                }
            },
            progress: function(ops,hidden){
                var 
                    box =  this,
                    data = box.data()
                ;
                //console.log(hidden);
                if(hidden){
                    var
                        max = parseFloat(box.data('max')) || 100
                    ;
                    data.countingFrom = parseFloat(box.data('from')) || 0.5;
                    data.countingFrom = 100/max * data.countingFrom;
                    data.countingTo = parseFloat(box.data('to')) || parseFloat(box.text()) || 0.5;
                    data.countingTo = 100/max * data.countingTo;
                    
                    data.set = function(i){
                        box.css({
                            'width': i +'%'
                        });
                    }
                    data.set(data.countingFrom);
                }else{
                    setTimeout(function(){
                        $(data).animate({
                            countingFrom: data.countingTo
                        },{
                            duration: ops.duration,
                            step: data.set
                        });
                    },ops.delay);
                }
            }
        }
    });
})(jQuery);