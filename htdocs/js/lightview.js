//  Lightview 2.2.9.2 - 21-05-2008
//  Copyright (c) 2008 Nick Stakenburg (http://www.nickstakenburg.com)
//
//  Licensed under a Creative Commons Attribution-No Derivative Works License
//  http://creativecommons.org/licenses/by-nd/3.0/
//  More information on this project:
//  http://www.nickstakenburg.com/projects/lightview/
var Lightview = {
  Version: '2.2.9.2',

  // Configuration
  options: {
    backgroundColor: '#ffffff',                            // Background color of the view
    border: 12,                                            // Size of the border
    buttons: {
      opacity: {                                           // Opacity of inner buttons
        disabled: 0.4,
        normal: 0.75,
        hover: 1
      },
      side: { display: true },                             // show side buttons
      innerPreviousNext: { display: true },                // show the inner previous and next button
      slideshow: { display: true }                         // show slideshow button
    },
    cyclic: false,                                         // Makes galleries cyclic, no end/begin.
    // The directory of the images, relative to this file or an absolute url
    images: (window.parent.document.location.host.match(/yigg\.de$/)
      ? window.parent.document.location.protocol + '//' + 'yigg.de/' : '')
      + 'images/lightview/',
    imgNumberTemplate: 'Image #{position} of #{total}',    // Want a different language? change it here
    keyboard: { enabled: true },                           // Enabled the keyboard buttons
    overlay: {                                             // Overlay
      background: '#000',                                  // Background color, Mac Firefox & Safari use overlay.png
      close: true,                                         // Overlay click closes the view
      opacity: 0.85,
      display: true
    },
    preloadHover: true,                                    // Preload images on mouseover
    radius: 12,                                            // Corner radius of the border
    removeTitles: true,                                    // Set to false if you want to keep title attributes intact
    resizeDuration: 0.9,                                   // When effects are used, the duration of resizing in seconds
    slideshowDelay: 5,                                     // Seconds to wait before showing the next slide in slideshow
    titleSplit: '::',                                      // The characters you want to split title with
    transition: function(pos) {                            // Or your own transition
      return ((pos/=0.5) < 1 ? 0.5 * Math.pow(pos, 4) :
        -0.5 * ((pos-=2) * Math.pow(pos,3) - 2));
    },
    viewport: true,                                        // Stay within the viewport, true is recommended
    zIndex: 5000,                                          // zIndex of #lightview, #overlay is this -1

    // Optional
    closeDimensions: {                                     // If you've changed the close button you can change these
      large: { width: 85, height: 22 },                    // not required but it speeds things up.
      small: { width: 32, height: 22 },
      innertop: { width: 22, height: 22 },
      topclose: { width: 22, height: 18 }                  // when topclose option is used
    },
    defaultOptions : {                                     // Default open dimensions for each type
      ajax:   { width: 400, height: 300 },
      iframe: { width: 400, height: 300, scrolling: true },
      inline: { width: 400, height: 300 },
      flash:  { width: 400, height: 300 },
      quicktime: { width: 480, height: 220, autoplay: true, controls: true, topclose: true }
    },
    sideDimensions: { width: 16, height: 22 }              // see closeDimensions
  },

  classids: {
    quicktime: 'clsid:02BF25D5-8C17-4B23-BC80-D3488ABDDC6B',
    flash: 'clsid:D27CDB6E-AE6D-11cf-96B8-444553540000'
  },
  codebases: {
    quicktime: 'http://www.apple.com/qtactivex/qtplugin.cab#version=7,3,0,0',
    flash: 'http://fpdownload.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=9,0,115,0'
  },
  errors: {
    requiresPlugin: "<div class='message'>The content your are attempting to view requires the <span class='type'>#{type}</span> plugin.</div><div class='pluginspage'><p>Please download and install the required plugin from:</p><a href='#{pluginspage}' target='_blank'>#{pluginspage}</a></div>"
  },
  mimetypes: {
    quicktime: 'video/quicktime',
    flash: 'application/x-shockwave-flash'
  },
  pluginspages: {
    quicktime: 'http://www.apple.com/quicktime/download',
    flash: 'http://www.adobe.com/go/getflashplayer'
  },
  // used with auto detection
  typeExtensions: {
    flash: 'swf',
    image: 'bmp gif jpeg jpg png',
    iframe: 'asp aspx cgi cfm htm html jsp php pl php3 php4 php5 phtml rb rhtml shtml txt',
    quicktime: 'avi mov mpg mpeg movie'
  }
};

Lightview.IEVersion = (function(B) {
    var A = new RegExp("MSIE ([\\d.]+)").exec(B);
    return A ? parseFloat(A[1]) : -1
})(navigator.userAgent);
Object.extend(Prototype.Browser, {
    IE6: Prototype.Browser.IE && (Lightview.IEVersion >= 6 && Lightview.IEVersion < 7),
    WebKit419: (Prototype.Browser.WebKit && !document.evaluate)
});
Object.extend(Lightview, {
    REQUIRED_Prototype: "1.6.0.2",
    REQUIRED_Scriptaculous: "1.8.1",
    queue: {
        position: "end",
        scope: "lightview"
    },
    isMac: !!navigator.userAgent.match(/mac/i),
    pngOverlay: !!navigator.userAgent.match(/mac/i) && (Prototype.Browser.WebKit || Prototype.Browser.Gecko),
    require: function(A) {
        if ((typeof window[A] == "undefined") || (this.convertVersionString(window[A].Version) < this.convertVersionString(this["REQUIRED_" + A]))) {
            throw ("Lightview requires " + A + " >= " + this["REQUIRED_" + A]);
        }
    },
    convertVersionString: function(A) {
        var B = A.replace(/_.*|\./g, "");
        B = parseInt(B + "0".times(4 - B.length));
        return A.indexOf("_") > -1 ? B - 1 : B
    },
    load: function() {
        this.require("Prototype");
        if ( !! window.Effect && !window.Scriptaculous) {
            this.require("Scriptaculous")
        }
        if (this.options.images.include("://")) {
            this.images = this.options.images
        } else {
            var A = /lightview(?:-[\w\d.]+)?\.js(.*)/;
            this.images = (($$("head script[src]").find(function(B) {
                return B.src.match(A)
            }) || {}).src || "").replace(A, "") + this.options.images
        }
        if (Prototype.Browser.IE && !document.namespaces.v) {
            document.namespaces.add("v", "urn:schemas-microsoft-com:vml");
            document.observe("dom:loaded",
            function() {
                document.createStyleSheet().addRule("v\\:*", "behavior: url(#default#VML);")
            })
        }
    },
    start: function() {
        this.radius = this.options.radius;
        this.border = (this.radius > this.options.border) ? this.radius: this.options.border;
        this.closeDimensions = this.options.closeDimensions;
        this.sideDimensions = this.options.sideDimensions;
        this.build();
        this.updateViews();
        this.addObservers()
    },
    build: function() {
        var B, I, D = this.pixelClone(this.sideDimensions);
        $(document.body).insert(this.overlay = new Element("div", {
            id: "overlay"
        }).setStyle({
            zIndex: this.options.zIndex - 1,
            position: (!(Prototype.Browser.Gecko || Prototype.Browser.IE6)) ? "fixed": "absolute",
            background: this.pngOverlay ? "url(" + this.images + "overlay.png) top left repeat": this.options.overlay.background
        }).setOpacity((Prototype.Browser.Gecko) ? 1 : this.options.overlay.opacity).hide()).insert(this.lightview = new Element("div", {
            id: "lightview"
        }).setStyle({
            zIndex: this.options.zIndex,
            top: "-10000px",
            left: "-10000px"
        }).setOpacity(0).insert(this.container = new Element("div", {
            className: "lv_Container"
        }).insert(this.sideButtons = new Element("ul", {
            className: "lv_Sides"
        }).insert(this.prevSide = new Element("li", {
            className: "lv_PrevSide"
        }).setStyle(I = Object.extend({
            marginLeft: -1 * this.sideDimensions.width + "px"
        },
        D)).insert(this.prevButtonImage = new Element("div", {
            className: "lv_Wrapper"
        }).setStyle(Object.extend({
            marginLeft: this.sideDimensions.width + "px"
        },
        D)).insert(new Element("div", {
            className: "lv_Button"
        })))).insert(this.nextSide = new Element("li", {
            className: "lv_NextSide"
        }).setStyle(Object.extend({
            marginRight: -1 * this.sideDimensions.width + "px"
        },
        D)).insert(this.nextButtonImage = new Element("div", {
            className: "lv_Wrapper"
        }).setStyle(I).insert(new Element("div", {
            className: "lv_Button"
        }))))).insert(this.topButtons = new Element("div", {
            className: "lv_topButtons"
        }).insert(this.topcloseButtonImage = new Element("div", {
            className: "lv_Wrapper lv_topcloseButtonImage"
        }).insert(this.topcloseButton = new Element("div", {
            className: "lv_Button"
        })))).insert(new Element("ul", {
            className: "lv_Frames"
        }).insert(new Element("li", {
            className: "lv_Frame lv_FrameTop"
        }).insert(B = new Element("div", {
            className: "lv_Liquid"
        }).setStyle({
            height: this.border + "px"
        }).insert(new Element("ul", {
            className: "lv_Half lv_HalfLeft"
        }).insert(new Element("li", {
            className: "lv_CornerWrapper"
        }).insert(new Element("div", {
            className: "lv_Corner"
        })).insert(new Element("div", {
            className: "lv_Fill"
        }).setStyle({
            left: this.border + "px"
        })))).insert(new Element("div", {
            className: "lv_Filler"
        })).insert(new Element("ul", {
            className: "lv_Half lv_HalfRight"
        }).insert(new Element("li", {
            className: "lv_CornerWrapper"
        }).setStyle("margin-top: " + ( - 1 * this.border) + "px").insert(new Element("div", {
            className: "lv_Corner"
        })).insert(new Element("div", {
            className: "lv_Fill"
        }).setStyle("left: " + ( - 1 * this.border) + "px")))))).insert(this.resizeCenter = new Element("li", {
            className: "lv_Center"
        }).setStyle("height: " + (150 - this.border) + "px").insert(new Element("div", {
            className: "lv_WrapUp"
        }).insert(new Element("div", {
            className: "lv_WrapDown"
        }).setStyle("margin-top: " + this.border + "px").insert(this.center = new Element("div", {
            className: "lv_WrapCenter"
        }).setOpacity(0).setStyle("padding: 0 " + this.border + "px").insert(this.media = new Element("div", {
            className: "lv_Media lv_Fill"
        })).insert(this.menubar = new Element("div", {
            className: "lv_MenuBar"
        }).insert(this.closeButton = new Element("div", {
            className: "lv_Button lv_Close"
        }).setStyle(this.pixelClone(this.options.closeDimensions.large)).setStyle({
            background: this.options.backgroundColor
        }).setOpacity(this.options.buttons.opacity.normal)).insert(this.data = new Element("ul", {
            className: "lv_Data"
        }).insert(this.dataText = new Element("li", {
            className: "lv_DataText"
        }).insert(this.title = new Element("div", {
            className: "lv_Title"
        })).insert(this.caption = new Element("div", {
            className: "lv_Caption"
        }))).insert(this.imgNumber = new Element("li", {
            className: "lv_ImgNumber"
        }).insert(new Element("div"))).insert(this.innerPrevNext = new Element("li", {
            className: "lv_innerPrevNext"
        }).insert(this.innerPrevButton = new Element("div", {
            className: "lv_Button"
        }).setOpacity(this.options.buttons.opacity.normal).setStyle({
            backgroundColor: this.options.backgroundColor
        }).setPngBackground(this.images + "inner_prev.png", {
            backgroundColor: this.options.backgroundColor
        })).insert(this.innerNextButton = new Element("div", {
            className: "lv_Button"
        }).setOpacity(this.options.buttons.opacity.normal).setStyle({
            backgroundColor: this.options.backgroundColor
        }).setPngBackground(this.images + "inner_next.png", {
            backgroundColor: this.options.backgroundColor
        }))).insert(this.slideshow = new Element("li", {
            className: "lv_Slideshow"
        }).insert(this.slideshowButton = new Element("div", {
            className: "lv_Button"
        }).setOpacity(this.options.buttons.opacity.normal).setStyle({
            backgroundColor: this.options.backgroundColor
        }).setPngBackground(this.images + "inner_slideshow_play.png", {
            backgroundColor: this.options.backgroundColor
        }))))).insert(this.external = new Element("div", {
            className: "lv_External"
        }))))).insert(this.loading = new Element("div", {
            className: "lv_Loading"
        }).insert(this.loadingButton = new Element("div", {
            className: "lv_Button"
        }).setStyle("background: url(" + this.images + "loading.gif) top left no-repeat")))).insert(new Element("li", {
            className: "lv_Frame lv_FrameBottom"
        }).insert(B.cloneNode(true))).insert(this.prevnext = new Element("li", {
            className: "lv_PrevNext"
        }).hide().setStyle("margin-top: " + this.border + "px; background: url(" + this.images + "blank.gif) top left repeat"))))).insert(new Element("div", {
            id: "lightviewError"
        }).hide());
        var H = new Image();
        H.onload = function() {
            H.onload = Prototype.emptyFunction;
            this.sideDimensions = {
                width: H.width,
                height: H.height
            };
            var K = this.pixelClone(this.sideDimensions),
            C;
            this.sideButtons.setStyle({
                marginTop: 0 - (H.height / 2).round() + "px",
                height: H.height + "px"
            });
            this.prevSide.setStyle(C = Object.extend({
                marginLeft: -1 * this.sideDimensions.width + "px"
            },
            K));
            this.prevButtonImage.setStyle(Object.extend({
                marginLeft: K.width
            },
            K));
            this.nextSide.setStyle(Object.extend({
                marginRight: -1 * this.sideDimensions.width + "px"
            },
            K));
            this.nextButtonImage.setStyle(C)
        }.bind(this);
        H.src = this.images + "prev.png";
        $w("center title caption imgNumber").each(function(C) {
            this[C].setStyle({
                backgroundColor: this.options.backgroundColor
            })
        }.bind(this));
        var G = this.container.select(".lv_Corner");
        $w("tl tr bl br").each(function(K, C) {
            if (this.radius > 0) {
                this.createCorner(G[C], K)
            } else {
                G[C].insert(new Element("div", {
                    className: "lv_Fill"
                }))
            }
            G[C].setStyle({
                width: this.border + "px",
                height: this.border + "px"
            }).addClassName("lv_Corner" + K.capitalize())
        }.bind(this));
        this.lightview.select(".lv_Filler", ".lv_Fill", ".lv_WrapDown").invoke("setStyle", {
            backgroundColor: this.options.backgroundColor
        });
        var E = {};
        $w("prev next topclose").each(function(K) {
            this[K + "ButtonImage"].side = K;
            var C = this.images + K + ".png";
            if (K == "topclose") {
                E[K] = new Image();
                E[K].onload = function() {
                    E[K].onload = Prototype.emptyFunction;
                    this.closeDimensions[K] = {
                        width: E[K].width,
                        height: E[K].height
                    };
                    var L = this.isMac ? "left": "right",
                    M = Object.extend({
                        "float": L,
                        marginTop: this.closeDimensions[K].height + "px"
                    },
                    this.pixelClone(this.closeDimensions[K]));
                    M["padding" + L.capitalize()] = this.border + "px";
                    this[K + "ButtonImage"].setStyle(M);
                    this.topButtons.setStyle({
                        height: E[K].height + "px",
                        top: -1 * this.closeDimensions[K].height + "px"
                    });
                    this[K + "ButtonImage"].down().setPngBackground(C).setStyle(this.pixelClone(this.closeDimensions[K]))
                }.bind(this);
                E[K].src = this.images + K + ".png"
            } else {
                this[K + "ButtonImage"].setPngBackground(C)
            }
        }.bind(this));
        var A = {};
        $w("large small innertop").each(function(C) {
            A[C] = new Image();
            A[C].onload = function() {
                A[C].onload = Prototype.emptyFunction;
                this.closeDimensions[C] = {
                    width: A[C].width,
                    height: A[C].height
                }
            }.bind(this);
            A[C].src = this.images + "close_" + C + ".png"
        }.bind(this));
        var J = new Image();
        J.onload = function() {
            J.onload = Prototype.emptyFunction;
            this.loading.setStyle({
                width: J.width + "px",
                height: J.height + "px",
                marginTop: -0.5 * J.height + 0.5 * this.border + "px",
                marginLeft: -0.5 * J.width + "px"
            })
        }.bind(this);
        J.src = this.images + "loading.gif";
        var F = new Image();
        F.onload = function() {
            F.onload = Prototype.emptyFunction;
            var C = {
                width: F.width + "px",
                height: F.height + "px"
            };
            this.slideshow.setStyle(C);
            this.slideshowButton.setStyle(C)
        }.bind(this);
        F.src = this.images + "inner_slideshow_play.png";
        $w("prev next").each(function(L) {
            var K = L.capitalize(),
            C = new Image();
            C.onload = function() {
                C.onload = Prototype.emptyFunction;
                this["inner" + K + "Button"].setStyle({
                    width: C.width + "px",
                    height: C.height + "px"
                })
            }.bind(this);
            C.src = this.images + "inner_" + L + ".png";
            this["inner" + K + "Button"].prevnext = L
        }.bind(this))
    },
    prepare: function() {
        Effect.Queues.get("lightview").each(function(A) {
            A.cancel()
        });
        this.scaledInnerDimensions = null;
        this.restoreInlineContent();
        this.views = null
    },
    restoreInlineContent: function() {
        if (!this.inlineContent || !this.inlineMarker) {
            return
        }
        this.inlineMarker.insert({
            after: this.inlineContent.setStyle({
                display: this.inlineContent._inlineDisplayRestore
            })
        });
        this.inlineMarker.remove();
        this.inlineMarker = null
    },
    show: function(B) {
        this.element = null;
        if (Object.isElement(B) || Object.isString(B)) {
            this.element = $(B);
            if (!this.element) {
                return
            }
            this.element.blur();
            this.view = this.element._view
        } else {
            if (B.href) {
                this.element = $(document.body);
                this.view = new Lightview.View(B)
            } else {
                if (Object.isNumber(B)) {
                    this.element = this.getSet(this.view.rel).elements[B];
                    this.view = this.element._view
                }
            }
        }
        if (!this.view.href) {
            return
        }
        this.prepare();
        this.disableKeyboardNavigation();
        this.hideOverlapping();
        this.hideContent();
        this.restoreCenter();
        this.appear();
        if (this.view.href != "#lightviewError" && Object.keys(Lightview.Plugin).join(" ").indexOf(this.view.type) >= 0) {
            if (!Lightview.Plugin[this.view.type]) {
                $("lightviewError").update(new Template(this.errors.requiresPlugin).evaluate({
                    type: this.view.type.capitalize(),
                    pluginspage: this.pluginspages[this.view.type]
                }));
                var C = $("lightviewError").getDimensions();
                this.show({
                    href: "#lightviewError",
                    title: this.view.type.capitalize() + " plugin required",
                    options: C
                });
                return false
            }
        }
        if (this.view.isGallery()) {
            this.views = this.view.isGallery() ? this.getViews(this.view.rel) : [this.view]
        }
        var A = Object.extend({
            menubar: true,
            topclose: false,
            wmode: "transparent",
            innerPreviousNext: this.view.isGallery() && this.options.buttons.innerPreviousNext.display,
            slideshow: this.view.isGallery() && this.options.buttons.slideshow.display
        },
        this.options.defaultOptions[this.view.type] || {});
        this.view.options = Object.extend(A, this.view.options);
        if (! (this.view.title || this.view.caption || (this.views && this.views.length > 1)) && this.view.options.topclose) {
            this.view.options.menubar = false
        }
        if (this.view.isImage()) {
            if (this.view.isGallery()) {
                this.position = this.views.indexOf(this.view);
                this.preloadSurroundingImages()
            }
            this.innerDimensions = this.view.preloadedDimensions;
            if (this.innerDimensions) {
                this.afterEffect()
            } else {
                this.startLoading();
                var D = new Image();
                D.onload = function() {
                    D.onload = Prototype.emptyFunction;
                    this.stopLoading();
                    this.innerDimensions = {
                        width: D.width,
                        height: D.height
                    };
                    this.afterEffect()
                }.bind(this);
                D.src = this.view.href
            }
        } else {
            this.innerDimensions = this.view.options.fullscreen ? document.viewport.getDimensions() : {
                width: this.view.options.width,
                height: this.view.options.height
            };
            this.afterEffect()
        }
    },
    insertContent: function() {
        var D = this.detectExtension(this.view.href),
        A = this.scaledInnerDimensions || this.innerDimensions;
        if (this.view.isImage()) {
            var B = this.pixelClone(A);
            this.media.setStyle(B).update(new Element("img", {
                id: "lightviewContent",
                src: this.view.href,
                alt: "",
                galleryimg: "no"
            }).setStyle(B))
        } else {
            if (this.view.isExternal()) {
                if (this.scaledInnerDimensions && this.view.options.fullscreen) {
                    A.height -= this.menuBarDimensions.height
                }
                switch (this.view.type) {
                case "ajax":
                    var F = Object.clone(this.view.options.ajax) || {};
                    var E = function() {
                        this.stopLoading();
                        if (this.view.options.autosize) {
                            this.external.setStyle({
                                width: "auto",
                                height: "auto"
                            });
                            this.innerDimensions = this.getHiddenDimensions(this.external)
                        }
                        new Effect.Event({
                            queue: this.queue,
                            afterFinish: function(){
                                this.resizeWithinViewport();
                                NinjaCommander.initialize();
                            }.bind(this)
                        })
                    }.bind(this);
                    if (F.onComplete) {
                        F.onComplete = F.onComplete.wrap(function(N, M) {
                            E();
                            N(M)
                        })
                    } else {
                        F.onComplete = E
                    }
                    this.startLoading();
                    new Ajax.Updater(this.external, this.view.href, F);
                    break;
                case "iframe":
                    this.external.update(this.iframe = new Element("iframe", {
                        frameBorder: 0,
                        hspace: 0,
                        src: this.view.href,
                        id: "lightviewContent",
                        name: "lightviewContent_" + (Math.random() * 99999).round(),
                        scrolling: (this.view.options && this.view.options.scrolling) ? "auto": "no"
                    }).setStyle(Object.extend({
                        border: 0,
                        margin: 0,
                        padding: 0
                    },
                    this.pixelClone(A))));
                    break;
                case "inline":
                    var C = this.view.href,
                    H = $(C.substr(C.indexOf("#") + 1));
                    if (!H || !H.tagName) {
                        return
                    }
                    var L = new Element(this.view.options.wrapperTag || "div"),
                    G = H.getStyle("visibility"),
                    J = H.getStyle("display");
                    H.wrap(L);
                    H.setStyle({
                        visibility: "hidden"
                    }).show();
                    var I = this.getHiddenDimensions(L);
                    H.setStyle({
                        visibility: G,
                        display: J
                    });
                    L.insert({
                        before: H
                    }).remove();
                    H.insert({
                        before: this.inlineMarker = new Element(H.tagName)
                    });
                    H._inlineDisplayRestore = H.getStyle("display");
                    this.inlineContent = H.show();
                    this.external.update(this.inlineContent);
                    this.external.select("select, object, embed").each(function(M) {
                        this.overlappingRestore.each(function(N) {
                            if (N.element == M) {
                                M.setStyle({
                                    visibility: N.visibility
                                })
                            }
                        })
                    }.bind(this));
                    if (this.view.options.autosize) {
                        this.innerDimensions = I;
                        new Effect.Event({
                            queue: this.queue,
                            afterFinish: this.resizeWithinViewport.bind(this)
                        })
                    }
                    break
                }
            } else {
                var K = {
                    tag: "object",
                    id: "lightviewContent",
                    width: A.width,
                    height: A.height
                };
                switch (this.view.type) {
                case "quicktime":
                    Object.extend(K, {
                        pluginspage: this.pluginspages[this.view.type],
                        children: [{
                            tag: "param",
                            name: "autoplay",
                            value: this.view.options.autoplay
                        },
                        {
                            tag: "param",
                            name: "scale",
                            value: "tofit"
                        },
                        {
                            tag: "param",
                            name: "controller",
                            value: this.view.options.controls
                        },
                        {
                            tag: "param",
                            name: "enablejavascript",
                            value: true
                        },
                        {
                            tag: "param",
                            name: "src",
                            value: this.view.href
                        },
                        {
                            tag: "param",
                            name: "loop",
                            value: this.view.options.loop || false
                        }]
                    });
                    Object.extend(K, Prototype.Browser.IE ? {
                        codebase: this.codebases[this.view.type],
                        classid: this.classids[this.view.type]
                    }: {
                        data: this.view.href,
                        type: this.mimetypes[this.view.type]
                    });
                    break;
                case "flash":
                    Object.extend(K, {
                        data: this.view.href,
                        type: this.mimetypes[this.view.type],
                        quality: "high",
                        wmode: this.view.options.wmode,
                        pluginspage: this.pluginspages[this.view.type],
                        children: [{
                            tag: "param",
                            name: "movie",
                            value: this.view.href
                        },
                        {
                            tag: "param",
                            name: "allowFullScreen",
                            value: "true"
                        }]
                    });
                    if (this.view.options.flashvars) {
                        K.children.push({
                            tag: "param",
                            name: "FlashVars",
                            value: this.view.options.flashvars
                        })
                    }
                    break
                }
                this.media.setStyle(this.pixelClone(A)).show();
                this.media.update(this.createHTML(K));
                if (this.view.isQuicktime() && $("lightviewContent")) { (function() {
                        try {
                            if ("SetControllerVisible" in $("lightviewContent")) {
                                $("lightviewContent").SetControllerVisible(this.view.options.controls)
                            }
                        } catch(M) {}
                    }.bind(this)).delay(0.4)
                }
            }
        }
    },
    getHiddenDimensions: function(B) {
        B = $(B);
        var A = B.ancestors(),
        C = [],
        E = [];
        A.push(B);
        A.each(function(F) {
            if (F != B && F.visible()) {
                return
            }
            C.push(F);
            E.push({
                display: F.getStyle("display"),
                position: F.getStyle("position"),
                visibility: F.getStyle("visibility")
            });
            F.setStyle({
                display: "block",
                position: "absolute",
                visibility: "visible"
            })
        });
        var D = {
            width: B.clientWidth,
            height: B.clientHeight
        };
        C.each(function(G, F) {
            G.setStyle(E[F])
        });
        return D
    },
    clearContent: function() {
        var A = $("lightviewContent");
        if (A) {
            switch (A.tagName.toLowerCase()) {
            case "object":
                if (Prototype.Browser.WebKit && this.view.isQuicktime()) {
                    try {
                        A.Stop()
                    } catch(B) {}
                    A.innerHTML = ""
                }
                if (A.parentNode) {
                    A.remove()
                } else {
                    A = Prototype.emptyFunction
                }
                break;
            case "iframe":
                A.remove();
                if (Prototype.Browser.Gecko) {
                    delete window.frames.lightviewContent
                }
                break;
            default:
                A.remove();
                break
            }
        }
    },
    adjustDimensionsToView: function() {
        var A = this.scaledInnerDimensions || this.innerDimensions;
        if (this.view.options.controls) {
            switch (this.view.type) {
            case "quicktime":
                A.height += 16;
                break
            }
        }
        this[(this.scaledInnerDimensions ? "scaledI": "i") + "nnerDimensions"] = A
    },
    afterEffect: function() {
        new Effect.Event({
            queue: this.queue,
            afterFinish: function() {
                this.afterShow()
            }.bind(this)
        })
    },
    afterShow: function() {
        this.fillMenuBar();
        if (!this.view.isAjax()) {
            this.stopLoading()
        }
        if (! ((this.view.options.autosize && this.view.isInline()) || this.view.isAjax())) {
            this.resizeWithinViewport()
        }
        if (!this.view.isIframe()) {
            new Effect.Event({
                queue: this.queue,
                afterFinish: this.insertContent.bind(this)
            })
        }
    },
    finishShow: function() {
        new Effect.Event({
            queue: this.queue,
            afterFinish: this.showContent.bind(this)
        });
        if (this.view.isIframe()) {
            new Effect.Event({
                delay: 0.2,
                queue: this.queue,
                afterFinish: this.insertContent.bind(this)
            })
        }
        if (this.sliding) {
            new Effect.Event({
                queue: this.queue,
                afterFinish: this.nextSlide.bind(this)
            })
        }
    },
    previous: function() {
        this.show(this.getSurroundingIndexes().previous)
    },
    next: function() {
        this.show(this.getSurroundingIndexes().next)
    },
    resizeWithinViewport: function() {
        this.adjustDimensionsToView();
        var B = this.getInnerDimensions(),
        D = this.getBounds();
        if (this.options.viewport && (B.width > D.width || B.height > D.height)) {
            if (!this.view.options.fullscreen) {
                var E = Object.clone(this.getOuterDimensions()),
                A = D,
                C = Object.clone(E);
                if (C.width > A.width) {
                    C.height *= A.width / C.width;
                    C.width = A.width;
                    if (C.height > A.height) {
                        C.width *= A.height / C.height;
                        C.height = A.height
                    }
                } else {
                    if (C.height > A.height) {
                        C.width *= A.height / C.height;
                        C.height = A.height;
                        if (C.width > A.width) {
                            C.height *= A.width / C.width;
                            C.width = A.width
                        }
                    }
                }
                var F = (C.width % 1 > 0 ? C.height / E.height: C.height % 1 > 0 ? C.width / E.width: 1);
                this.scaledInnerDimensions = {
                    width: (this.innerDimensions.width * F).round(),
                    height: (this.innerDimensions.height * F).round()
                };
                this.fillMenuBar();
                B = {
                    width: this.scaledInnerDimensions.width,
                    height: this.scaledInnerDimensions.height + this.menuBarDimensions.height
                }
            } else {
                this.scaledInnerDimensions = D;
                this.fillMenuBar();
                B = D
            }
        } else {
            this.fillMenuBar();
            this.scaledInnerDimensions = null
        }
        this.resize(B)
    },
    resize: function(B) {
        var F = this.lightview.getDimensions(),
        I = 2 * this.border,
        D = B.width + I,
        M = B.height + I;
        this.hidePrevNext();
        var L = function() {
            this.restoreCenter();
            this.resizing = null;
            this.finishShow()
        };
        if (F.width == D && F.height == M) {
            L.bind(this)();
            return
        }
        var C = {
            width: D + "px",
            height: M + "px"
        };
        if (!Prototype.Browser.IE6) {
            Object.extend(C, {
                marginLeft: 0 - D / 2 + "px",
                marginTop: 0 - M / 2 + "px"
            })
        }
        var G = D - F.width,
        K = M - F.height,
        J = parseInt(this.lightview.getStyle("marginLeft").replace("px", "")),
        E = parseInt(this.lightview.getStyle("marginTop").replace("px", ""));
        if (!Prototype.Browser.IE6) {
            var A = (0 - D / 2) - J,
            H = (0 - M / 2) - E
        }
        this.resizing = new Effect.Tween(this.lightview, 0, 1, {
            duration: this.options.resizeDuration,
            queue: this.queue,
            transition: this.options.transition,
            afterFinish: L.bind(this)
        },
        function(Q) {
            var N = (F.width + Q * G).toFixed(0),
            P = (F.height + Q * K).toFixed(0);
            if (Prototype.Browser.IE6) {
                this.lightview.setStyle({
                    width: (F.width + Q * G).toFixed(0) + "px",
                    height: (F.height + Q * K).toFixed(0) + "px"
                });
                this.resizeCenter.setStyle({
                    height: P - 1 * this.border + "px"
                })
            } else {
                if (Prototype.Browser.IE) {
                    this.lightview.setStyle({
                        position: "fixed",
                        width: N + "px",
                        height: P + "px",
                        marginLeft: ((0 - N) / 2).round() + "px",
                        marginTop: ((0 - P) / 2).round() + "px"
                    });
                    this.resizeCenter.setStyle({
                        height: P - 1 * this.border + "px"
                    })
                } else {
                    var O = this.getViewportDimensions(),
                    R = document.viewport.getScrollOffsets();
                    this.lightview.setStyle({
                        position: "absolute",
                        marginLeft: 0,
                        marginTop: 0,
                        width: N + "px",
                        height: P + "px",
                        left: (R[0] + (O.width / 2) - (N / 2)).floor() + "px",
                        top: (R[1] + (O.height / 2) - (P / 2)).floor() + "px"
                    });
                    this.resizeCenter.setStyle({
                        height: P - 1 * this.border + "px"
                    })
                }
            }
        }.bind(this))
    },
    showContent: function() {
        new Effect.Event({
            queue: this.queue,
            afterFinish: Element.show.bind(this, this[this.view.isMedia() ? "media": "external"])
        });
        new Effect.Event({
            queue: this.queue,
            afterFinish: this.hidePrevNext.bind(this)
        });
        new Effect.Parallel([new Effect.Opacity(this.center, {
            sync: true,
            from: 0,
            to: 1
        }), new Effect.Appear(this.sideButtons, {
            sync: true
        })], {
            queue: this.queue,
            duration: 0.45,
            afterFinish: function() {
                if (this.element) {
                    this.element.fire("lightview:opened")
                }
            }.bind(this)
        });
        if (this.view.isGallery()) {
            new Effect.Event({
                queue: this.queue,
                afterFinish: this.showPrevNext.bind(this)
            })
        }
    },
    hideContent: function() {
        if (!this.lightview.visible()) {
            return
        }
        new Effect.Parallel([new Effect.Opacity(this.sideButtons, {
            sync: true,
            from: 1,
            to: 0
        }), new Effect.Opacity(this.center, {
            sync: true,
            from: 1,
            to: 0
        })], {
            queue: this.queue,
            duration: 0.35
        });
        new Effect.Event({
            queue: this.queue,
            afterFinish: function() {
                this.clearContent();
                this.media.update("").hide();
                this.external.update("").hide();
                this.topcloseButtonImage.setStyle({
                    marginTop: this.closeDimensions.topclose.height + "px"
                })
            }.bind(this)
        })
    },
    hideData: function() {
        this.dataText.hide();
        this.title.hide();
        this.caption.hide();
        this.imgNumber.hide();
        this.innerPrevNext.hide();
        this.slideshow.hide()
    },
    fillMenuBar: function() {
        this.hideData();
        if (!this.view.options.menubar) {
            this.menuBarDimensions = {
                width: 0,
                height: 0
            };
            this.closeButtonWidth = 0;
            this.menubar.hide();
            return false
        } else {
            this.menubar.show()
        }
        this.menubar[(this.view.isExternal() ? "add": "remove") + "ClassName"]("lv_MenuTop");
        if (this.view.title || this.view.caption) {
            this.dataText.show()
        }
        if (this.view.title) {
            this.title.update(this.view.title).show()
        }
        if (this.view.caption) {
            this.caption.update(this.view.caption).show()
        }
        if (this.views && this.views.length > 1) {
            this.imgNumber.show().down().update(new Template(this.options.imgNumberTemplate).evaluate({
                position: this.position + 1,
                total: this.views.length
            }));
            if (this.view.options.slideshow) {
                this.slideshowButton.show();
                this.slideshow.show()
            }
        }
        if (this.view.options.innerPreviousNext && this.views.length > 1) {
            var A = {
                prev: (this.options.cyclic || this.position != 0),
                next: (this.options.cyclic || (this.view.isGallery() && this.getSurroundingIndexes().next != 0))
            };
            $w("prev next").each(function(B) {
                this["inner" + B.capitalize() + "Button"].setStyle({
                    cursor: (A[B] ? "pointer": "auto")
                }).setOpacity(A[B] ? this.options.buttons.opacity.normal: this.options.buttons.opacity.disabled)
            }.bind(this));
            this.innerPrevNext.show()
        }
        this.setCloseButtons();
        this.setMenuBarDimensions()
    },
    setCloseButtons: function() {
        var E = this.closeDimensions.small.width,
        D = this.closeDimensions.large.width,
        G = this.closeDimensions.innertop.width,
        A = this.scaledInnerDimensions ? this.scaledInnerDimensions.width: this.innerDimensions.width,
        F = 180,
        C = 0,
        B = this.options.borderColor;
        if (this.view.options.topclose) {
            B = null
        } else {
            if (!this.view.isMedia()) {
                B = "innertop";
                C = G
            } else {
                if (A >= F + E && A < F + D) {
                    B = "small";
                    C = E
                } else {
                    if (A >= F + D) {
                        B = "large";
                        C = D
                    }
                }
            }
        }
        if (C > 0) {
            this.closeButton.setStyle({
                width: C + "px"
            }).show()
        } else {
            this.closeButton.hide()
        }
        if (B) {
            this.closeButton.setPngBackground(this.images + "close_" + B + ".png", {
                backgroundColor: this.options.backgroundColor
            })
        }
        this.closeButtonWidth = C
    },
    startLoading: function() {
        this.loadingEffect = new Effect.Appear(this.loading, {
            duration: 0.3,
            from: 0,
            to: 1,
            queue: this.queue
        })
    },
    stopLoading: function() {
        if (this.loadingEffect) {
            Effect.Queues.get("lightview").remove(this.loadingEffect)
        }
        new Effect.Fade(this.loading, {
            duration: 1,
            queue: this.queue
        })
    },
    setPrevNext: function() {
        if (!this.view.isImage()) {
            return
        }
        var D = (this.options.cyclic || this.position != 0),
        B = (this.options.cyclic || (this.view.isGallery() && this.getSurroundingIndexes().next != 0));
        this.prevButtonImage[D ? "show": "hide"]();
        this.nextButtonImage[B ? "show": "hide"]();
        var C = this.scaledInnerDimensions || this.innerDimensions;
        this.prevnext.setStyle({
            height: C.height + "px"
        });
        var A = ((C.width / 2 - 1) + this.border).floor();
        if (D) {
            this.prevnext.insert(this.prevButton = new Element("div", {
                className: "lv_Button lv_PrevButton"
            }).setStyle({
                width: A + "px"
            }));
            this.prevButton.side = "prev"
        }
        if (B) {
            this.prevnext.insert(this.nextButton = new Element("div", {
                className: "lv_Button lv_NextButton"
            }).setStyle({
                width: A + "px"
            }));
            this.nextButton.side = "next"
        }
        if (D || B) {
            this.prevnext.show()
        }
    },
    showPrevNext: function() {
        if (!this.options.buttons.side.display || !this.view.isImage()) {
            return
        }
        this.setPrevNext();
        this.prevnext.show()
    },
    hidePrevNext: function() {
        this.prevnext.update("").hide();
        this.prevButtonImage.hide().setStyle({
            marginLeft: this.sideDimensions.width + "px"
        });
        this.nextButtonImage.hide().setStyle({
            marginLeft: -1 * this.sideDimensions.width + "px"
        })
    },
    appear: function() {
        if (this.lightview.getStyle("opacity") != 0) {
            return
        }
        var A = function() {
            if (!Prototype.Browser.WebKit419) {
                this.lightview.show()
            }
            this.lightview.setOpacity(1)
        }.bind(this);
        if (this.options.overlay.display) {
            new Effect.Appear(this.overlay, {
                duration: 0.4,
                from: 0,
                to: this.pngOverlay ? 1 : this.options.overlay.opacity,
                queue: this.queue,
                beforeStart: this.maxOverlay.bind(this),
                afterFinish: A
            })
        } else {
            A()
        }
    },
    hide: function() {
        if (Prototype.Browser.IE && this.iframe && this.view.isIframe()) {
            this.iframe.remove()
        }
        if (Prototype.Browser.WebKit419 && this.view.isQuicktime()) {
            var A = $$("object#lightviewContent")[0];
            if (A) {
                try {
                    A.Stop()
                } catch(B) {}
            }
        }
        if (this.lightview.getStyle("opacity") == 0) {
            return
        }
        this.stopSlideshow();
        this.prevnext.hide();
        if (!Prototype.Browser.IE || !this.view.isIframe()) {
            this.center.hide()
        }
        if (Effect.Queues.get("lightview_hide").effects.length > 0) {
            return
        }
        Effect.Queues.get("lightview").each(function(C) {
            C.cancel()
        });
        new Effect.Event({
            queue: this.queue,
            afterFinish: this.restoreInlineContent.bind(this)
        });
        new Effect.Opacity(this.lightview, {
            duration: 0.1,
            from: 1,
            to: 0,
            queue: {
                position: "end",
                scope: "lightview_hide"
            }
        });
        new Effect.Fade(this.overlay, {
            duration: 0.4,
            queue: {
                position: "end",
                scope: "lightview_hide"
            },
            afterFinish: this.afterHide.bind(this)
        })
    },
    afterHide: function() {
        if (!Prototype.Browser.WebKit419) {
            this.lightview.hide()
        } else {
            this.lightview.setStyle({
                marginLeft: "-10000px",
                marginTop: "-10000px"
            })
        }
        this.center.setOpacity(0).show();
        this.prevnext.update("").hide();
        this.clearContent();
        this.media.update("").hide();
        this.external.update("").hide();
        this.disableKeyboardNavigation();
        this.showOverlapping();
        if (this.element) {
            this.element.fire("lightview:hidden")
        }
        this.element = null;
        this.views = null;
        this.view = null;
        this.scaledInnerDimensions = null
    },
    setMenuBarDimensions: function() {
        var B = {},
        A = this[(this.scaledInnerDimensions ? "scaledI": "i") + "nnerDimensions"].width;
        this.menubar.setStyle({
            width: A + "px"
        });
        this.data.setStyle({
            width: A - this.closeButtonWidth - 1 + "px"
        });
        B = this.getHiddenDimensions(this.menubar);
        this.menubar.setStyle({
            width: "100%"
        });
        this.menuBarDimensions = this.view.options.menubar ? B: {
            width: B.width,
            height: 0
        }
    },
    restoreCenter: function() {
        var B = this.lightview.getDimensions();
        if (Prototype.Browser.IE6) {
            this.lightview.setStyle({
                top: "50%",
                left: "50%"
            })
        } else {
            if (Prototype.Browser.WebKit419 || Prototype.Browser.Gecko) {
                var A = this.getViewportDimensions(),
                C = document.viewport.getScrollOffsets();
                this.lightview.setStyle({
                    marginLeft: 0,
                    marginTop: 0,
                    left: (C[0] + (A.width / 2) - (B.width / 2)).floor() + "px",
                    top: (C[1] + (A.height / 2) - (B.height / 2)).floor() + "px"
                })
            } else {
                this.lightview.setStyle({
                    position: "fixed",
                    left: "50%",
                    top: "50%",
                    marginLeft: (0 - B.width / 2).round() + "px",
                    marginTop: (0 - B.height / 2).round() + "px"
                })
            }
        }
    },
    startSlideshow: function() {
        this.stopSlideshow();
        this.sliding = true;
        this.next.bind(this).delay(0.25);
        this.slideshowButton.setPngBackground(this.images + "inner_slideshow_stop.png", {
            backgroundColor: this.options.backgroundColor
        }).hide()
    },
    stopSlideshow: function() {
        if (this.sliding) {
            this.sliding = false
        }
        if (this.slideTimer) {
            clearTimeout(this.slideTimer)
        }
        this.slideshowButton.setPngBackground(this.images + "inner_slideshow_play.png", {
            backgroundColor: this.options.backgroundColor
        })
    },
    toggleSlideshow: function() {
        this[(this.sliding ? "stop": "start") + "Slideshow"]()
    },
    nextSlide: function() {
        if (this.sliding) {
            this.slideTimer = this.next.bind(this).delay(this.options.slideshowDelay)
        }
    },
    updateViews: function() {
        this.sets = [];
        var A = $$("a[class~=lightview]");
        A.each(function(B) {
            B.stopObserving();
            new Lightview.View(B);
            B.observe("click", this.show.curry(B).wrap(function(E, D) {
                D.stop();
                E(D)
            }).bindAsEventListener(this));
            if (B._view.isImage()) {
                if (this.options.preloadHover) {
                    B.observe("mouseover", this.preloadImageHover.bind(this, B._view))
                }
                var C = A.partition(function(D) {
                    return D.rel == B.rel
                });
                if (C[0].length) {
                    this.sets.push({
                        rel: B._view.rel,
                        elements: C[0]
                    });
                    A = C[1]
                }
            }
        }.bind(this))
    },
    getSet: function(A) {
        return this.sets.find(function(B) {
            return B.rel == A
        })
    },
    getViews: function(A) {
        return this.getSet(A).elements.pluck("_view")
    },
    addObservers: function() {
        $(document.body).observe("click", this.delegateClose.bindAsEventListener(this));
        $w("mouseover mouseout").each(function(C) {
            this.prevnext.observe(C,
            function(D) {
                var E = D.findElement("div");
                if (!E) {
                    return
                }
                if (this.prevButton && this.prevButton == E || this.nextButton && this.nextButton == E) {
                    this.toggleSideButton(D)
                }
            }.bindAsEventListener(this))
        }.bind(this));
        this.prevnext.observe("click",
        function(D) {
            var E = D.findElement("div");
            if (!E) {
                return
            }
            var C = (this.prevButton && this.prevButton == E) ? "previous": (this.nextButton && this.nextButton == E) ? "next": null;
            if (C) {
                this[C].wrap(function(G, F) {
                    this.stopSlideshow();
                    G(F)
                }).bind(this)()
            }
        }.bindAsEventListener(this));
        $w("prev next").each(function(F) {
            var E = F.capitalize(),
            C = function(H, G) {
                this.stopSlideshow();
                H(G)
            },
            D = function(I, H) {
                var G = H.element().prevnext;
                if ((G == "prev" && (this.options.cyclic || this.position != 0)) || (G == "next" && (this.options.cyclic || (this.view.isGallery() && this.getSurroundingIndexes().next != 0)))) {
                    I(H)
                }
            };
            this[F + "ButtonImage"].observe("mouseover", this.toggleSideButton.bindAsEventListener(this)).observe("mouseout", this.toggleSideButton.bindAsEventListener(this)).observe("click", this[F == "next" ? F: "previous"].wrap(C).bindAsEventListener(this));
            this["inner" + E + "Button"].observe("click", this[F == "next" ? F: "previous"].wrap(D).bindAsEventListener(this)).observe("mouseover", Element.setOpacity.curry(this["inner" + E + "Button"], this.options.buttons.opacity.hover).wrap(D).bindAsEventListener(this)).observe("mouseout", Element.setOpacity.curry(this["inner" + E + "Button"], this.options.buttons.opacity.normal).wrap(D).bindAsEventListener(this))
        }.bind(this));
        var B = [this.closeButton, this.slideshowButton];
        if (!Prototype.Browser.WebKit419) {
            B.each(function(C) {
                C.observe("mouseover", Element.setOpacity.bind(this, C, this.options.buttons.opacity.hover)).observe("mouseout", Element.setOpacity.bind(this, C, this.options.buttons.opacity.normal))
            }.bind(this))
        } else {
            B.invoke("setOpacity", 1)
        }
        this.slideshowButton.observe("click", this.toggleSlideshow.bindAsEventListener(this));
        if (Prototype.Browser.WebKit419 || Prototype.Browser.Gecko) {
            var A = function(D, C) {
                if (this.lightview.getStyle("top").charAt(0) == "-") {
                    return
                }
                D(C)
            };
            Event.observe(window, "scroll", this.restoreCenter.wrap(A).bindAsEventListener(this));
            Event.observe(window, "resize", this.restoreCenter.wrap(A).bindAsEventListener(this))
        }
        if (Prototype.Browser.Gecko) {
            Event.observe(window, "resize", this.maxOverlay.bindAsEventListener(this))
        }
        this.lightview.observe("mouseover", this.toggleTopClose.bindAsEventListener(this)).observe("mouseout", this.toggleTopClose.bindAsEventListener(this));
        this.topcloseButton.observe("mouseover", this.toggleTopClose.bindAsEventListener(this)).observe("mouseout", this.toggleTopClose.bindAsEventListener(this))
    },
    toggleTopClose: function(C) {
        var B = C.type;
        if (!this.view) {
            B = "mouseout"
        } else {
            if (! (this.view && this.view.options && this.view.options.topclose && (this.center.getOpacity() == 1))) {
                return
            }
        }
        if (this.topCloseEffect) {
            Effect.Queues.get("lightview_topCloseEffect").remove(this.topCloseEffect)
        }
        var A = {
            marginTop: ((B == "mouseover") ? 0 : this.closeDimensions.topclose.height) + "px"
        };
        this.topCloseEffect = new Effect.Morph(this.topcloseButtonImage, {
            style: A,
            duration: 0.2,
            queue: {
                scope: "lightview_topCloseEffect",
                limit: 1
            },
            delay: (B == "mouseout" ? 0.3 : 0)
        })
    },
    getScrollDimensions: function() {
        var A = {};
        $w("width height").each(function(E) {
            var C = E.capitalize();
            var B = document.documentElement;
            A[E] = Prototype.Browser.IE ? [B["offset" + C], B["scroll" + C]].max() : Prototype.Browser.WebKit ? document.body["scroll" + C] : B["scroll" + C]
        });
        return A
    },
    maxOverlay: function() {
        if (!Prototype.Browser.Gecko) {
            return
        }
        this.overlay.setStyle(this.pixelClone(document.viewport.getDimensions()));
        this.overlay.setStyle(this.pixelClone(this.getScrollDimensions()))
    },
    delegateClose: function(A) {
        if (!this.delegateCloseElements) {
            this.delegateCloseElements = [this.closeButton, this.topButtons, this.loadingButton, this.topcloseButton];
            if (this.options.overlay.close) {
                this.delegateCloseElements.push(this.overlay)
            }
        }
        if (A.target && (this.delegateCloseElements.include(A.target))) {
            this.hide()
        }
    },
    toggleSideButton: function(E) {
        var C = E.target,
        B = C.side,
        A = this.sideDimensions.width,
        F = (E.type == "mouseover") ? 0 : B == "prev" ? A: -1 * A,
        D = {
            marginLeft: F + "px"
        };
        if (!this.sideEffect) {
            this.sideEffect = {}
        }
        if (this.sideEffect[B]) {
            Effect.Queues.get("lightview_side" + B).remove(this.sideEffect[B])
        }
        this.sideEffect[B] = new Effect.Morph(this[B + "ButtonImage"], {
            style: D,
            duration: 0.2,
            queue: {
                scope: "lightview_side" + B,
                limit: 1
            },
            delay: (E.type == "mouseout" ? 0.1 : 0)
        })
    },
    getSurroundingIndexes: function() {
        if (!this.views) {
            return
        }
        var D = this.position,
        C = this.views.length;
        var B = (D <= 0) ? C - 1 : D - 1,
        A = (D >= C - 1) ? 0 : D + 1;
        return {
            previous: B,
            next: A
        }
    },
    createCorner: function(G, H) {
        var F = arguments[2] || this.options,
        B = F.radius,
        E = F.border,
        D = new Element("canvas", {
            className: "cornerCanvas" + H.capitalize(),
            width: E + "px",
            height: E + "px"
        }),
        A = {
            top: (H.charAt(0) == "t"),
            left: (H.charAt(1) == "l")
        };
        if (D && D.getContext && D.getContext("2d")) {
            G.insert(D);
            var C = D.getContext("2d");
            C.fillStyle = F.backgroundColor;
            C.arc((A.left ? B: E - B), (A.top ? B: E - B), B, 0, Math.PI * 2, true);
            C.fill();
            C.fillRect((A.left ? B: 0), 0, E - B, E);
            C.fillRect(0, (A.top ? B: 0), E, E - B)
        } else {
            G.insert(new Element("div").setStyle({
                width: E + "px",
                height: E + "px",
                margin: 0,
                padding: 0,
                display: "block",
                position: "relative",
                overflow: "hidden"
            }).insert(new Element("v:roundrect", {
                fillcolor: F.backgroundColor,
                strokeWeight: "1px",
                strokeColor: F.backgroundColor,
                arcSize: (B / E * 0.5).toFixed(2)
            }).setStyle({
                width: 2 * E - 1 + "px",
                height: 2 * E - 1 + "px",
                position: "absolute",
                left: (A.left ? 0 : ( - 1 * E)) + "px",
                top: (A.top ? 0 : ( - 1 * E)) + "px"
            })))
        }
    },
    hideOverlapping: function() {
        if (this.preventingOverlap) {
            return
        }
        var A = $$("select", "embed", "object");
        this.overlappingRestore = A.map(function(B) {
            return {
                element: B,
                visibility: B.getStyle("visibility")
            }
        });
        A.invoke("setStyle", "visibility:hidden");
        this.preventingOverlap = true
    },
    showOverlapping: function() {
        this.overlappingRestore.each(function(B, A) {
            B.element.setStyle("visibility: " + B.visibility)
        });
        delete this.overlappingRestore;
        this.preventingOverlap = false
    },
    pixelClone: function(A) {
        var B = {};
        Object.keys(A).each(function(C) {
            B[C] = A[C] + "px"
        });
        return B
    },
    getInnerDimensions: function() {
        return {
            width: this.innerDimensions.width,
            height: this.innerDimensions.height + this.menuBarDimensions.height
        }
    },
    getOuterDimensions: function() {
        var B = this.getInnerDimensions(),
        A = 2 * this.border;
        return {
            width: B.width + A,
            height: B.height + A
        }
    },
    getBounds: function() {
        var C = 20,
        A = 2 * this.sideDimensions.height + C,
        B = this.getViewportDimensions();
        return {
            width: B.width - A,
            height: B.height - A
        }
    },
    getViewportDimensions: function() {
        var A = document.viewport.getDimensions();
        if (this.controller && this.controller.visible()) {
            A.height -= this.controllerOffset
        }
        return A
    }
});
Object.extend(Lightview, {
    enableKeyboardNavigation: function() {
        if (!this.options.keyboard.enabled) {
            return
        }
        this.keyboardEvent = this.keyboardDown.bindAsEventListener(this);
        document.observe("keydown", this.keyboardEvent)
    },
    disableKeyboardNavigation: function() {
        if (!this.options.keyboard.enabled) {
            return
        }
        if (this.keyboardEvent) {
            document.stopObserving("keydown", this.keyboardEvent)
        }
    },
    keyboardDown: function(C) {
        var B = String.fromCharCode(C.keyCode).toLowerCase(),
        E = C.keyCode,
        F = this.view.isGallery() && !this.resizing,
        A = this.view.options.slideshow,
        D;
        if (this.view.isMedia()) {
            C.stop();
            D = (E == Event.KEY_ESC || ["x", "c"].member(B)) ? "hide": (E == 37 && F && (this.options.cyclic || this.position != 0)) ? "previous": (E == 39 && F && (this.options.cyclic || this.getSurroundingIndexes().next != 0)) ? "next": (B == "p" && A && this.view.isGallery()) ? "startSlideshow": (B == "s" && A && this.view.isGallery()) ? "stopSlideshow": null;
            if (B != "s") {
                this.stopSlideshow()
            }
        } else {
            D = (E == Event.KEY_ESC) ? "hide": null
        }
        if (D) {
            this[D]()
        }
        if (F) {
            if (E == Event.KEY_HOME && this.views.first() != this.view) {
                this.show(this.views.first())
            }
            if (E == Event.KEY_END && this.views.last() != this.view) {
                this.show(this.views.last())
            }
        }
    }
});
Lightview.afterShow = Lightview.afterShow.wrap(function(B, A) {
    this.enableKeyboardNavigation();
    B(A)
});
Object.extend(Lightview, {
    preloadSurroundingImages: function() {
        if (this.views.length == 0) {
            return
        }
        var A = this.getSurroundingIndexes();
        this.preloadFromSet([A.next, A.previous])
    },
    preloadFromSet: function(C) {
        var A = (this.views && this.views.member(C) || Object.isArray(C)) ? this.views: C.rel ? this.getViews(C.rel) : null;
        if (!A) {
            return
        }
        var B = $A(Object.isNumber(C) ? [C] : C.type ? [A.indexOf(C)] : C).uniq();
        B.each(function(F) {
            var D = A[F],
            E = D.href;
            if (D.preloadedDimensions || D.isPreloading || !E) {
                return
            }
            var G = new Image();
            G.onload = function() {
                G.onload = Prototype.emptyFunction;
                D.isPreloading = null;
                this.setPreloadedDimensions(D, G)
            }.bind(this);
            G.src = E
        }.bind(this))
    },
    setPreloadedDimensions: function(A, B) {
        A.preloadedDimensions = {
            width: B.width,
            height: B.height
        }
    },
    preloadImageHover: function(A) {
        if (A.preloadedDimensions || A.isPreloading) {
            return
        }
        this.preloadFromSet(A)
    }
});
Element.addMethods({
    setPngBackground: function(C, B) {
        C = $(C);
        var A = Object.extend({
            align: "top left",
            repeat: "no-repeat",
            sizingMethod: "scale",
            backgroundColor: ""
        },
        arguments[2] || {});
        C.setStyle(Prototype.Browser.IE6 ? {
            filter: "progid:DXImageTransform.Microsoft.AlphaImageLoader(src='" + B + "'', sizingMethod='" + A.sizingMethod + "')"
        }: {
            background: A.backgroundColor + " url(" + B + ") " + A.align + " " + A.repeat
        });
        return C
    }
});
Object.extend(Lightview, {
    detectType: function(A) {
        var B;
        $w("flash image iframe quicktime").each(function(C) {
            if (new RegExp("\\.(" + this.typeExtensions[C].replace(/\s+/g, "|") + ")(\\?.*)?", "i").test(A)) {
                B = C
            }
        }.bind(this));
        if (B) {
            return B
        }
        if (A.startsWith("#")) {
            return "inline"
        }
        if (document.domain && document.domain != (A).replace(/(^.*\/\/)|(:.*)|(\/.*)/g, "")) {
            return "iframe"
        }
        return "image"
    },
    detectExtension: function(A) {
        var B = A.gsub(/\?.*/, "").match(/\.([^.]{3,4})$/);
        return B ? B[1] : null
    },
    createHTML: function(B) {
        var C = "<" + B.tag;
        for (var A in B) {
            if (! ["children", "html", "tag"].member(A)) {
                C += " " + A + '="' + B[A] + '"'
            }
        }
        if (new RegExp("^(?:area|base|basefont|br|col|frame|hr|img|input|link|isindex|meta|param|range|spacer|wbr)$", "i").test(B.tag)) {
            C += "/>"
        } else {
            C += ">";
            if (B.children) {
                B.children.each(function(D) {
                    C += this.createHTML(D)
                }.bind(this))
            }
            if (B.html) {
                C += B.html
            }
            C += "</" + B.tag + ">"
        }
        return C
    }
}); (function() {
    document.observe("dom:loaded",
    function() {
        var B = (navigator.plugins && navigator.plugins.length),
        A = function(D) {
            var C = false;
            if (B) {
                C = ($A(navigator.plugins).pluck("name").join(",").indexOf(D) >= 0)
            } else {
                try {
                    C = new ActiveXObject(D)
                } catch(E) {}
            }
            return !! C
        };
        window.Lightview.Plugin = (B) ? {
            flash: A("Shockwave Flash"),
            quicktime: A("QuickTime")
        }: {
            flash: A("ShockwaveFlash.ShockwaveFlash"),
            quicktime: A("QuickTime.QuickTime")
        }
    })
})();
Lightview.View = Class.create({
    initialize: function(b) {
        var c = Object.isElement(b);
        if (c && !b._view) {
            b._view = this;
            if (b.title) {
                b._view._title = b.title;
                if (Lightview.options.removeTitles) {
                    b.title = ""
                }
            }
        }
        this.href = c ? b.getAttribute("href") : b.href;
        if (this.href.indexOf("#") >= 0) {
            this.href = this.href.substr(this.href.indexOf("#"))
        }
        if (b.rel && b.rel.startsWith("gallery")) {
            this.type = "gallery";
            this.rel = b.rel
        } else {
            if (b.rel) {
                this.type = b.rel;
                this.rel = b.rel
            } else {
                this.type = Lightview.detectType(this.href);
                this.rel = this.type
            }
        }
        $w("ajax flash gallery iframe image inline quicktime external media").each(function(a) {
            var T = a.capitalize(),
            t = a.toLowerCase();
            if ("image gallery media external".indexOf(a) < 0) {
                this["is" + T] = function() {
                    return this.type == t
                }.bind(this)
            }
        }.bind(this));
        if (c && b._view._title) {
            var d = b._view._title.split(Lightview.options.titleSplit).invoke("strip");
            if (d[0]) {
                this.title = d[0]
            }
            if (d[1]) {
                this.caption = d[1]
            }
            var e = d[2];
            this.options = (e && Object.isString(e)) ? eval("({" + e + "})") : {}
        } else {
            this.title = b.title;
            this.caption = b.caption;
            this.options = b.options || {}
        }
        if (this.options.ajaxOptions) {
            this.options.ajax = Object.clone(this.options.ajaxOptions);
            delete this.options.ajaxOptions
        }
    },
    isGallery: function() {
        return this.type.startsWith("gallery")
    },
    isImage: function() {
        return (this.isGallery() || this.type == "image")
    },
    isExternal: function() {
        return "iframe inline ajax".indexOf(this.type) >= 0
    },
    isMedia: function() {
        return ! this.isExternal()
    }
});
Lightview.load();
document.observe("dom:loaded", Lightview.start.bind(Lightview));