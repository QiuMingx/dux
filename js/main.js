if (!window.console) {
    window.console = {
        log: function () {}
    }
}


/*!
 * jQuery resizeend - A jQuery plugin that allows for window resize-end event handling.
 * 
 * Copyright (c) 2015 Erik Nielsen
 * 
 * Licensed under the MIT license:
 *    http://www.opensource.org/licenses/mit-license.php
 * 
 * Project home:
 *    http://312development.com
 * 
 * Version:  0.2.0
 * 
 */
! function (a) {
    var b = window.Chicago || {
        utils: {
            now: Date.now || function () {
                return (new Date).getTime()
            },
            uid: function (a) {
                return (a || "id") + b.utils.now() + "RAND" + Math.ceil(1e5 * Math.random())
            },
            is: {
                number: function (a) {
                    return !isNaN(parseFloat(a)) && isFinite(a)
                },
                fn: function (a) {
                    return "function" == typeof a
                },
                object: function (a) {
                    return "[object Object]" === Object.prototype.toString.call(a)
                }
            },
            debounce: function (a, b, c) {
                var d;
                return function () {
                    var e = this,
                        f = arguments,
                        g = function () {
                            d = null, c || a.apply(e, f)
                        },
                        h = c && !d;
                    d && clearTimeout(d), d = setTimeout(g, b), h && a.apply(e, f)
                }
            }
        },
        $: window.jQuery || null
    };
    if ("function" == typeof define && define.amd && define("chicago", function () {
            return b.load = function (a, c, d, e) {
                var f = a.split(","),
                    g = [],
                    h = (e.config && e.config.chicago && e.config.chicago.base ? e.config.chicago.base : "").replace(/\/+$/g, "");
                if (!h) throw new Error("Please define base path to jQuery resize.end in the requirejs config.");
                for (var i = 0; i < f.length;) {
                    var j = f[i].replace(/\./g, "/");
                    g.push(h + "/" + j), i += 1
                }
                c(g, function () {
                    d(b)
                })
            }, b
        }), window && window.jQuery) return a(b, window, window.document);
    if (!window.jQuery) throw new Error("jQuery resize.end requires jQuery")
}(function (a, b, c) {
    a.$win = a.$(b), a.$doc = a.$(c), a.events || (a.events = {}), a.events.resizeend = {
            defaults: {
                delay: 250
            },
            setup: function () {
                var b, c = arguments,
                    d = {
                        delay: a.$.event.special.resizeend.defaults.delay
                    };
                a.utils.is.fn(c[0]) ? b = c[0] : a.utils.is.number(c[0]) ? d.delay = c[0] : a.utils.is.object(c[0]) && (d = a.$.extend({}, d, c[0]));
                var e = a.utils.uid("resizeend"),
                    f = a.$.extend({
                        delay: a.$.event.special.resizeend.defaults.delay
                    }, d),
                    g = f,
                    h = function (b) {
                        g && clearTimeout(g), g = setTimeout(function () {
                            return g = null, b.type = "resizeend.chicago.dom", a.$(b.target).trigger("resizeend", b)
                        }, f.delay)
                    };
                return a.$(this).data("chicago.event.resizeend.uid", e), a.$(this).on("resize", a.utils.debounce(h, 100)).data(e, h)
            },
            teardown: function () {
                var b = a.$(this).data("chicago.event.resizeend.uid");
                return a.$(this).off("resize", a.$(this).data(b)), a.$(this).removeData(b), a.$(this).removeData("chicago.event.resizeend.uid")
            }
        },
        function () {
            a.$.event.special.resizeend = a.events.resizeend, a.$.fn.resizeend = function (b, c) {
                return this.each(function () {
                    a.$(this).on("resizeend", b, c)
                })
            }
        }()
});


/* 
 * jsui
 * ====================================================
 */
jsui.bd = $('body')
jsui.is_signin = jsui.bd.hasClass('logged-in') ? true : false;

if ($('.widget-nav').length) {
    $('.widget-nav li').each(function (e) {
        $(this).hover(function () {
            $(this).addClass('active').siblings().removeClass('active')
            $('.widget-navcontent .item:eq(' + e + ')').addClass('active').siblings().removeClass('active')
        })
    })
}

if ($('.sns-wechat').length) {
    $('.sns-wechat').on('click', function () {
        var _this = $(this)
        if (!$('#modal-wechat').length) {
            $('body').append('\
                <div class="modal fade" id="modal-wechat" tabindex="-1" role="dialog" aria-hidden="true">\
                    <div class="modal-dialog" style="margin-top:200px;width:340px;">\
                        <div class="modal-content">\
                            <div class="modal-header">\
                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>\
                                <h4 class="modal-title">' + _this.attr('title') + '</h4>\
                            </div>\
                            <div class="modal-body" style="text-align:center">\
                                <img style="max-width:100%" src="' + _this.data('src') + '">\
                            </div>\
                        </div>\
                    </div>\
                </div>\
            ')
        }
        $('#modal-wechat').modal()
    })
}


if ($('.carousel').length) {
    var el_carousel = $('.carousel')

    el_carousel.carousel({
        interval: 4000
    })

    tbquire(['hammer'], function (Hammer) {

        // window.Hammer = Hammer

        var mc = new Hammer(el_carousel[0]);

        mc.on("panleft panright swipeleft swiperight", function (ev) {
            if (ev.type == 'swipeleft' || ev.type == 'panleft') {
                el_carousel.carousel('next')
            } else if (ev.type == 'swiperight' || ev.type == 'panright') {
                el_carousel.carousel('prev')
            }
        });

    })
}


if (Number(jsui.ajaxpager) > 0 && ($('.excerpt').length || $('.excerpt-minic').length)) {
    tbquire(['ias'], function () {
        if (!jsui.bd.hasClass('site-minicat') && $('.excerpt').length) {
            $.ias({
                triggerPageThreshold: jsui.ajaxpager ? Number(jsui.ajaxpager) + 1 : 5,
                history: false,
                container: '.content',
                item: '.excerpt',
                pagination: '.pagination',
                next: '.next-page a',
                loader: '<div class="pagination-loading"><img src="' + jsui.uri + '/img/loading.gif"></div>',
                trigger: '更多',
                onRenderComplete: function () {
                    tbquire(['lazyload'], function () {
                        $('.excerpt .thumb').lazyload({
                            data_attribute: 'src',
                            placeholder: jsui.uri + '/img/thumbnail.png',
                            threshold: 400
                        });
                        
                    });
                }
            });
        }

        if (jsui.bd.hasClass('site-minicat') && $('.excerpt-minic').length) {
            $.ias({
                triggerPageThreshold: jsui.ajaxpager ? Number(jsui.ajaxpager) + 1 : 5,
                history: false,
                container: '.content',
                item: '.excerpt-minic',
                pagination: '.pagination',
                next: '.next-page a',
                loader: '<div class="pagination-loading"><img src="' + jsui.uri + '/img/loading.gif"></div>',
                trigger: '更多',
                onRenderComplete: function () {
                    tbquire(['lazyload'], function () {
                        $('.excerpt .thumb').lazyload({
                            data_attribute: 'src',
                            placeholder: jsui.uri + '/img/thumbnail.png',
                            threshold: 400
                        });
                    });
                }
            });
        }
    });
}
// 今日快讯
new Swiper('.today-swiper .swiper-container', {
    //          direction: 'vertical',
                direction: 'horizontal',
                slidesPerView: 1,
                spaceBetween: 20,
                mousewheel: true,
                loop: true,
                autoplay: {
                    delay: 6000,
                    disableOnInteraction: false,
               },
            });

// if (Number(jsui.ajaxpager) > 0 && ($('.excerpt').length || $('.excerpt-minic').length)) {
//     tbquire(['ias'], function () {
//         if (!jsui.bd.hasClass('site-minicat') && $('.excerpt-minic').length) {
//             $.ias({
//                 triggerPageThreshold: jsui.ajaxpager ? Number(jsui.ajaxpager) + 1 : 5,
//                 history: false,
//                 container: '.content',
//                 item: '.excerpt-minic',
//                 pagination: '.pagination',
//                 next: '.next-page a',
//                 loader: '<div class="pagination-loading"><img src="' + jsui.uri + '/img/loading.gif"></div>',
//                 trigger: '更多',
//                 onRenderComplete: function () {
//                     tbquire(['lazyload'], function () {
//                         $('.excerpt-minic .thumb').lazyload({
//                             data_attribute: 'src',
//                             placeholder: jsui.uri + '/img/thumbnail.png',
//                             threshold: 400
//                         });
                        
//                     });
//                 }
//             });
//         }

//         if (jsui.bd.hasClass('site-minicat') && $('.excerpt-minic').length) {
//             $.ias({
//                 triggerPageThreshold: jsui.ajaxpager ? Number(jsui.ajaxpager) + 1 : 5,
//                 history: false,
//                 container: '.content',
//                 item: '.excerpt-minic',
//                 pagination: '.pagination',
//                 next: '.next-page a',
//                 loader: '<div class="pagination-loading"><img src="' + jsui.uri + '/img/loading.gif"></div>',
//                 trigger: '更多',
//                 onRenderComplete: function () {
//                     tbquire(['lazyload'], function () {
//                         $('.excerpt-minic .thumb').lazyload({
//                             data_attribute: 'src',
//                             placeholder: jsui.uri + '/img/thumbnail.png',
//                             threshold: 400
//                         });
//                     });
//                 }
//             });
//         }
//     });
// }
/* 
 * lazyload
 * ====================================================
 */
tbquire(['lazyload'], function () {
    $('.avatar').lazyload({
        data_attribute: 'src',
        placeholder: jsui.uri + '/img/avatar-default.png',
        threshold: 400
    })

    $('.widget .avatar').lazyload({
        data_attribute: 'src',
        placeholder: jsui.uri + '/img/avatar-default.png',
        threshold: 400
    })

    $('.thumb').lazyload({
        data_attribute: 'src',
        placeholder: jsui.uri + '/img/thumbnail.png',
        threshold: 400
    })

    $('.widget_ui_posts .thumb').lazyload({
        data_attribute: 'src',
        placeholder: jsui.uri + '/img/thumbnail.png',
        threshold: 400
    })

    $('.wp-smiley').lazyload({
        data_attribute: 'src',
        // placeholder: jsui.uri + '/img/thumbnail.png',
        threshold: 400
    })
})



/* 
 * prettyprint
 * ====================================================
 */
$('pre').each(function () {
    if (!$(this).attr('style')) $(this).addClass('prettyprint')
})

if ($('.prettyprint').length) {
    tbquire(['prettyprint'], function (prettyprint) {
        prettyPrint()
    })
}



/* 
 * rollbar
 * ====================================================
 */
jsui.rb_comment = ''
if (jsui.bd.hasClass('comment-open')) {
    jsui.rb_comment = "<li><a href=\"javascript:(scrollTo('#comments',-15));\"><i class=\"fa fa-comments\"></i></a><h6>去评论<i></i></h6></li>"
}

jsui.bd.append('\
    <div class="m-mask"></div>\
    <div class="rollbar"><ul>' +
    jsui.rb_comment +
    '<li><a href="javascript:(scrollTo());"><i class="fa fa-angle-up"></i></a><h6>去顶部<i></i></h6></li>\
    </ul></div>\
')



var _wid = $(window).width()

$(window).resize(function (event) {
    _wid = $(window).width()
});



var scroller = $('.rollbar')
var _fix = (jsui.bd.hasClass('nav_fixed') && !jsui.bd.hasClass('page-template-navs')) ? true : false
$(window).scroll(function () {
    var h = document.documentElement.scrollTop + document.body.scrollTop

    if (_fix && h > 0 && _wid > 720) {
        jsui.bd.addClass('nav-fixed')
    } else {
        jsui.bd.removeClass('nav-fixed')
    }

    h > 200 ? scroller.fadeIn() : scroller.fadeOut();
})


/* 
 * bootstrap
 * ====================================================
 */
$('.user-welcome').tooltip({
    container: 'body',
    placement: 'bottom'
})



/* 
 * sign
 * ====================================================
 */
if (!jsui.bd.hasClass('logged-in')) {
    tbquire(['signpop'], function (signpop) {
        signpop.init()
    })
}


/* 
 * single
 * ====================================================
 */

var _sidebar = $('.sidebar')
if (_wid > 1024 && _sidebar.length) {
    var h1 = 15,
        h2 = 30
    var rollFirst = _sidebar.find('.widget:eq(' + (jsui.roll[0] - 1) + ')')
    var sheight = rollFirst.height()


    rollFirst.on('affix-top.bs.affix', function () {

        rollFirst.css({
            top: 0
        })
        sheight = rollFirst.height()

        for (var i = 1; i < jsui.roll.length; i++) {
            var item = jsui.roll[i] - 1
            var current = _sidebar.find('.widget:eq(' + item + ')')
            current.removeClass('affix').css({
                top: 0
            })
        };
    })

    rollFirst.on('affix.bs.affix', function () {

        rollFirst.css({
            top: jsui.bd.hasClass('nav-fixed') ? h1 + 63 : h1
        })

        for (var i = 1; i < jsui.roll.length; i++) {
            var item = jsui.roll[i] - 1
            var current = _sidebar.find('.widget:eq(' + item + ')')
            current.addClass('affix').css({
                top: jsui.bd.hasClass('nav-fixed') ? sheight + h2 + 63 : sheight + h2
            })
            sheight += current.height() + 15
        };
    })

    rollFirst.affix({
        offset: {
            top: _sidebar.height(),
            bottom: $('.footer').outerHeight()
        }
    })


}



$('[data-event="rewards"]').on('click', function () {
    $('.rewards-popover-mask, .rewards-popover').fadeIn()
})

$('[data-event="rewards-close"]').on('click', function () {
    $('.rewards-popover-mask, .rewards-popover').fadeOut()
})


if ($('#SOHUCS').length) $('#SOHUCS').before('<span id="comments"></span>')


/*$('.plinks a').each(function(){
    var imgSrc = $(this).attr('href')+'/favicon.ico'
    $(this).prepend( '<img src="'+imgSrc+'">' )
})*/


/* 
 * comment
 * ====================================================
 */
if (jsui.bd.hasClass('comment-open')) {
    tbquire(['comment'], function (comment) {
        comment.init()
    })
}


/* 
 * page u
 * ====================================================
 */
if (jsui.bd.hasClass('page-template-pagesuser-php')) {
    tbquire(['user'], function (user) {
        user.init()
    })
}


/* 
 * page nav
 * ====================================================
 */
if (jsui.bd.hasClass('page-template-pagesnavs-php')) {

    var titles = ''
    var i = 0
    $('#navs .items h2').each(function () {
        titles += '<li><a href="#' + i + '">' + $(this).text() + '</a></li>'
        i++
    })
    $('#navs nav ul').html(titles)

    $('#navs .items a').attr('target', '_blank')

    $('#navs nav ul').affix({
        offset: {
            top: $('#navs nav ul').offset().top,
            bottom: $('.footer').height() + $('.footer').css('padding-top').split('px')[0] * 2
        }
    })


    if (location.hash) {
        var index = location.hash.split('#')[1]
        $('#navs nav li:eq(' + index + ')').addClass('active')
        $('#navs nav .item:eq(' + index + ')').addClass('active')
        scrollTo('#navs .items .item:eq(' + index + ')')
    }
    $('#navs nav a').each(function (e) {
        $(this).click(function () {
            scrollTo('#navs .items .item:eq(' + $(this).parent().index() + ')')
            $(this).parent().addClass('active').siblings().removeClass('active')
        })
    })
}


/* 
 * page search
 * ====================================================
 */
if (jsui.bd.hasClass('search-results')) {
    var val = $('.site-search-form .search-input').val()
    var reg = eval('/' + val + '/i')
    $('.excerpt h2 a, .excerpt .note').each(function () {
        $(this).html($(this).text().replace(reg, function (w) {
            return '<b>' + w + '</b>'
        }))
    })
}


/* 
 * search
 * ====================================================
 */
$('.search-show').bind('click', function () {
    $(this).find('.fa').toggleClass('fa-remove')

    jsui.bd.toggleClass('search-on')

    if (jsui.bd.hasClass('search-on')) {
        $('.site-search').find('input').focus()
        jsui.bd.removeClass('m-nav-show')
    }
})


/* 
 * phone-share
 * ====================================================
 */
window.onscroll = function(){
    $('#iframe').slideUp(5000) ; 
}
// setTimeout(function(){
//     $('#iframe').slideUp(5000) ; 
// },3000);




if (document.getElementById("qrcode")) {
    var qrcode = new QRCode(document.getElementById("qrcode"), {
        width: 74,
        height: 74,
    });
}

$('.share_btn').bind('click', function () {
    var text = $(this).parent().parent(".excerpt-minic").children(".lives").text();
    var time = $(this).parent().parent(".excerpt-minic").children("time").attr('title');
    var imge = $(this).parent().parent(".excerpt-minic").children("img").attr('src')
    var link = $(this).parent().parent(".excerpt-minic").children("time").attr('data-href') ? $(this).parent().parent(".excerpt-minic").children("time").attr('data-href') : $('#qrcode').attr('data-href');
    console.log(link)
    makeCode(link);
    // console.log($('.article-title').children("a").attr('href'))
   
    if(text){
        text = "<b>" + text.replace(/】/, "】</b></br>")
        console.log(text)
        $('.star_level').children("img").attr('src',imge);
        $('.share_content').html(text);
        $('.share_main_time').children("span").html(time);
    }
    
    
    setTimeout(function () {
        showTip();
        convert2canvas();
    }, 200)

    $('#imgoverlay').click(hideTip);
    $('#share_imgBox').click(hideTip);
    $('#share_imgBox .shareImg img').click(function (e) {
        e.stopPropagation();
    });

});



function makeCode(val) {
    qrcode.clear();
    if (!val) {
        return;
    }
    qrcode.makeCode(val);
}

function convert2canvas() {
    var shareContent = document.getElementById('express');
    var imges = document.getElementById('imges')
    var reimge = document.getElementsByClassName('removeShareImg')[0];

    var width = shareContent.offsetWidth;
    var height = shareContent.offsetHeight;
    var canvas = document.createElement("canvas");
    var scale = 1;

    canvas.width = width * scale;
    canvas.height = height * scale;
    canvas.getContext("2d").scale(scale, scale);

    var opts = {
        scale: scale,
        canvas: canvas,
        logging: true,
        width: width,
        height: height
    };
   
    html2canvas(shareContent, opts).then(function (canvas) {
        var context = canvas.getContext('2d');

        var img = Canvas2Image.convertToImage(canvas, canvas.width, canvas.height, 'png');

        imges.appendChild(img)
        // imges.insertBefore(img, reimge)
        // document.body.appendChild(Canvas2Image.convertToImage(canvas, canvas.width, canvas.height,
        //     'png'));
        // var img = Canvas2Image.convertToImage(canvas, canvas.width, canvas.height, 'png');

        // $('#imges').append(img);
        // img.attr("class","canvasImg");
        // document.getElementById('#imges').appendChild(img);
        // alert($(window).height())
        console.log(img.width)
        $('#imges').css({
            // "width": /*canvas.width /2.5 + "px",*/$(window).width() *0.6 + 'px',
           "height": window.innerHeight*0.8 +"px",
           'margin-top':window.innerHeight*0.2*0.2 +"px",
        //    "width": window.innerWidth*0.5 +"px",
        //    "line-height":window.innerHeight*0.8 +"px"
            // "margin": "0 auto",
            // "position": "fixed",
            // "top": "3.2rem",
            // "bottom": "2.133333rem",
            // 'margin-left':img.width /(-2)+ 'px',
        })
        img.className = 'canvasImg';
        $('#share_imgBox .shareImg img').click(function (e) {

            e.stopPropagation();
        });


        $('.removeShareImg').css({
            'left': img.offsetLeft + img.offsetWidth -16+ 'px',
            'top': window.innerHeight*0.2*0.3-16 +"px",
        });
        // img.setAttribute('data-v-a611b654', '1')
    });

    //     var image = canvas.toDataURL("image/jpg");
    //         $('#express').parent('.shareImg').append('<div data-v-a611b654="" class="share_imgBox">' +
    //             '<div data-v-a611b654="" class="explain">请长按图片，将本条快讯推荐给好友</div>' +
    //             '<div data-v-a611b654="" class="shareImg" id="imgs">' 
    //               + '<img data-v-a611b654="" src="'+ image +'" class="canvasImg">'+
    //             '<div data-v-a611b654="" class="removeShareImg">' +
    //             '<img data-v-a611b654="" src="">' +
    //             '</div>' +
    //             '</div>');
}



function showTip() {
    $('#imgoverlay').css("display", "block");
    $('.share_imgBox').css('display', 'block');
    // document.documentElement.style.overflow="hidden";
    // document.documentElement.style.height="100%";
    // document.body.style.overflow="hidden";
    // document.body.style.height="100%";

}

function hideTip() {
    $('.share_imgBox').css("display", "none");
    $('#imgoverlay').css('display', 'none');
    $('.closebtn').css('display', 'none');
    $('#imges').empty();
    // document.documentElement.style.overflow="visible";
    // document.documentElement.style.height="auto";
    // document.body.style.overflow="visible";
    // document.body.style.height="auto";

    // $('.pic_share1 img').css({
    //     'width': '0',
    //     'height': '0'
    // });
    // document.getElementsByClassName('closebtn')[0].style.right = '0';
}






/* 
 * phone
 * ====================================================
 */

jsui.bd.append($('.site-navbar').clone().attr('class', 'm-navbar'))

$('.m-icon-nav').on('click', function () {
    jsui.bd.addClass('m-nav-show')

    $('.m-mask').show()

    jsui.bd.removeClass('search-on')
    $('.search-show .fa').removeClass('fa-remove')
})

$('.m-mask').on('click', function () {
    $(this).hide()
    jsui.bd.removeClass('m-nav-show')
})




if ($('.article-content').length) {
    $('.article-content img').attr('data-tag', 'bdshare')
}


video_ok()
$(window).resizeend(function (event) {
    video_ok()
});

function video_ok() {
    var cw = $('.article-content').width()
    $('.article-content embed, .article-content video, .article-content iframe').each(function () {
        var w = $(this).attr('width') || 0,
            h = $(this).attr('height') || 0
        if (cw && w && h) {
            $(this).css('width', cw < w ? cw : w)
            $(this).css('height', $(this).width() / (w / h))
        }
    })
}






/* functions
 * ====================================================
 */
function scrollTo(name, add, speed) {
    if (!speed) speed = 300
    if (!name) {
        $('html,body').animate({
            scrollTop: 0
        }, speed)
    } else {
        if ($(name).length > 0) {
            $('html,body').animate({
                scrollTop: $(name).offset().top + (add || 0)
            }, speed)
        }
    }
}


function is_name(str) {
    return /.{2,12}$/.test(str)
}

function is_url(str) {
    return /^((http|https)\:\/\/)([a-z0-9-]{1,}.)?[a-z0-9-]{2,}.([a-z0-9-]{1,}.)?[a-z0-9]{2,}$/.test(str)
}

function is_qq(str) {
    return /^[1-9]\d{4,13}$/.test(str)
}

function is_mail(str) {
    return /^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,4})$/.test(str)
}


$.fn.serializeObject = function () {
    var o = {};
    var a = this.serializeArray();
    $.each(a, function () {
        if (o[this.name] !== undefined) {
            if (!o[this.name].push) {
                o[this.name] = [o[this.name]];
            }
            o[this.name].push(this.value || '');
        } else {
            o[this.name] = this.value || '';
        }
    });
    return o;
};


function strToDate(str, fmt) { //author: meizz   
    if (!fmt) fmt = 'yyyy-MM-dd hh:mm:ss'
    str = new Date(str * 1000)
    var o = {
        "M+": str.getMonth() + 1, //月份   
        "d+": str.getDate(), //日   
        "h+": str.getHours(), //小时   
        "m+": str.getMinutes(), //分   
        "s+": str.getSeconds(), //秒   
        "q+": Math.floor((str.getMonth() + 3) / 3), //季度   
        "S": str.getMilliseconds() //毫秒   
    };
    if (/(y+)/.test(fmt))
        fmt = fmt.replace(RegExp.$1, (str.getFullYear() + "").substr(4 - RegExp.$1.length));
    for (var k in o)
        if (new RegExp("(" + k + ")").test(fmt))
            fmt = fmt.replace(RegExp.$1, (RegExp.$1.length == 1) ? (o[k]) : (("00" + o[k]).substr(("" + o[k]).length)));
    return fmt;
}





/* erphpdown 登录使用dux弹出登录框
 * ====================================================
 */
$('.erphp-login-must').each(function () {
    $(this).addClass('signin-loader')
})