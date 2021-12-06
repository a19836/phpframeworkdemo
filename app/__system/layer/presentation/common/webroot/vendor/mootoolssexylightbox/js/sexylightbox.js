/*
 * SexyLightBox v1.0 by Eduardo D. Sada (http://www.coders.me)
 * To be used with mootools 1.2
 *
 * Licensed under the MIT License:
 *   http://www.opensource.org/licenses/mit-license.php
 */
var SexyLightBox = new Class({
	getOptions: function(){
		return {
			name: 'TB',
			zIndex: 65555,
			onReturn: false,
			onReturnFunction : $empty,
			BoxStyles: {
				'width': 500,
				'height': 300
			},
			OverlayStyles: {
				'background-color': '#000',
				'opacity': 0.6
			},
			find        :'sexylightbox',
			color       :'black',
			hexcolor    :'#000000',
			captionColor:'#FFFFFF',
			showCaption: true,
			mainClassName:'SexyLightBox_cb',
			showDuration: 200,
			showEffect  : Fx.Transitions.linear,
      			closeDuration: 200,
			closeEffect : Fx.Transitions.linear,
			moveDuration: 800,
			moveEffect  : Fx.Transitions.Back.easeInOut,
			resizeDuration:800,
			resizeEffect: Fx.Transitions.Back.easeInOut
		};
	},

     initialize: function(options){
      this.isMSIE = navigator.userAgent.indexOf("MSIE") != -1 ? true : false;
      
      this.setOptions(this.getOptions(), options);

      if(Browser.Engine.trident4) {
        this.strBG = 'bg'+this.options.color+'IE';
        this.strBGIFrame = 'bg'+this.options.color+'IFrameIE';
      }
      else {
        this.strBG = 'bg'+this.options.color;
        this.strBGIFrame = 'bg'+this.options.color+'IFrame';
      }
      this.contenedorClassName = this.options.mainClassName;
      this.closeBtClassName = 'bgCloseBt bg'+this.options.color+'CloseBt';
      this.loadingBGClassName = 'bg'+this.options.color+'loading';
      this.errorBGClassName = 'bg'+this.options.color+'error';
      this.contenidoClassName = 'i1';
      this.contenidoBGClassName = 'bg'+this.options.color+'Contenido';
      
      this.options.display == 0;

      this.imageTypes = /\.(jpe?g|png|gif|bmp|jpg|tif|tiff|raw)$/gi;
      
      /**
       * Creamos los elementos
       ************************/
      this.Overlay = new Element('div', {
        'id': this.options.name+'_overlay',
        'styles': {
          'display': 'none',
          'z-index': this.options.zIndex,
          'position': 'absolute',
          'top': '0',
          'left': '0',
          'background-color': this.options.OverlayStyles['background-color'],
          'opacity': 0,
          'height': window.getScrollHeight() + 'px',
          'width': window.getScrollWidth() + 'px'
        }
      });
      
      this.Contenedor = new Element('div', {
        'class': this.contenedorClassName,
        'styles': {
          'display': 'none',
          'z-index': this.options.zIndex + 2,
          'position': 'absolute',
          'top': -this.options.BoxStyles['height']+'px',
          'left': (window.getScroll().x + (window.getSize().x - this.options.BoxStyles['width']) / 2).toInt()+'px',
          'width': this.options.BoxStyles['width'] + 'px'
        }
      });
      
      
      this.bt = new Element('div', {'class': 'bt '+this.strBG}).injectInside(this.Contenedor);
      
      this.CloseButton = new Element('a', {'href':'#'}).injectInside(this.bt);
      new Element('div', {'class':this.closeBtClassName}).injectInside(this.CloseButton);

      this.bt_conner = new Element('div',{'class':this.strBG}).injectInside(this.bt);
      
      this.Contenido = new Element('div', {
        'class':this.contenidoClassName,
        'styles':{
          'height': this.options.BoxStyles['height'] + 'px',
          'border-left-color': this.options.hexcolor,
          'border-right-color': this.options.hexcolor
        }
      }).injectInside(this.Contenedor);

      this.bb          = new Element('div', {'class':'bb '+this.strBG}).injectInside(this.Contenedor);
      this.innerbb     = new Element('div', {'class':'innerbb '+this.strBG}).injectInside(this.bb);
      this.Nav         = new Element('div', {'class':'nav','styles':{'color':this.options.captionColor}});
      this.Description = new Element('strong',{'styles':{'color':this.options.captionColor}});

      this.Overlay.injectInside(document.body);
      this.Contenedor.injectInside(document.body);
    
      /**
       * AGREGAMOS LOS EVENTOS
       ************************/

      this.CloseButton.addEvent('click', function(event) {
        event.stop();
        this.display(0);
      }.bind(this));

      window.addEvent('resize', function() {
        if(this.options.display == 1) {
          this.Overlay.setStyles({
            'height': window.getScrollHeight() + 'px',
            'width': window.getScrollWidth() + 'px'
          });
          this.replaceBox();
        }
      }.bind(this));

      window.addEvent('scroll', function() {
        if(this.options.display == 1) {
          this.replaceBox();
        }
      }.bind(this));
      
      this.Overlay.addEvent('click', function() {
        this.display(0);
      }.bind(this));

      this.initializeElms();
	
      this.MoveBox = $empty();
	
  },
  
  initializeElms: function(parent) {
  	if(parent) {
		if(typeof(parent) == "string") {//parent == ID
  			$$("#"+parent+" a."+this.options.find).each(function(el) {
			  $(el).addEvent('click', function(event) {
			    event.stop();
			    this.hook(el);
			  }.bind(this));
			}.bind(this));
  		}
  		else {
			if(parent.nodeName && parent.nodeName.toString() && parent.nodeName.toString().toUpperCase() == "A") {
  				$(parent).addEvent('click', function(event) {
				    event.stop();
				    this.hook(parent);
				}.bind(this));
  			}
  			else {
  				
				if(this.options.find) {
			  		$(parent).getElements("a."+this.options.find, true).each(function(el) {
			  		  $(el).addEvent('click', function(event) {
					    event.stop();
					    this.hook(el);
					  }.bind(this));
					}.bind(this));
				}
				else {
					$(parent).getElements("a").each(function(el) {
					  $(el).addEvent('click', function(event) {
					    event.stop();
					    this.hook(el);
					  }.bind(this));
					}.bind(this));
				}
			}
  		}
  	}
  	else {
		$$("a."+this.options.find).each(function(el) {
		  $(el).addEvent('click', function(event) {
		    event.stop();
		    this.hook(el);
		  }.bind(this));
		}.bind(this));
        }
  },
  
	/*
	Function: hook
		Recuperar los parametros del enlace, y enviarselos a la funcion show
		
	Argument:
		enlace - object, la referencia a un elemento link.
	*/	
  hook: function(enlace) {
    enlace.blur();
    this.show((enlace.title || enlace.name || ""), enlace.href, (enlace.rel || false), enlace);
  },




	/*
	Property: display
		Show or close box
		
	Argument:
		option - integer, 1 to Show box and 0 to close box (with a transition).
	*/	
  display: function(option) {
		// Detener la transicion por las dudas
		if(this.Transition)
			this.Transition.cancel();				

    // Mostrar lo sexy que es LightBox
		if(this.options.display == 0 && option != 0 || option == 1) {
			this.Overlay.setStyle('display', 'block');
			this.options.display = 1;
			this.fireEvent('onShowStart', [this.Overlay]);

			this.Transition = new Fx.Tween(this.Overlay,
				{
          				property: 'opacity',
					duration: this.options.showDuration,
					transition: this.options.showEffect,
					onComplete: function() {

						sizes = window.getSize();
						scrollito = window.getScroll();
						this.Contenedor.setStyles({
							'display': 'block',
							'left': (scrollito.x + (sizes.x - this.options.BoxStyles['width']) / 2).toInt()
						});

						this.fireEvent('onShowComplete', [this.Overlay]);

					}.bind(this)
				}
			).start(this.options.OverlayStyles['opacity']);

		}
		// Cerrar el Lightbox
		else
		{

			this.Contenedor.setStyles({
				'display': 'none',
				'top': 0
			});
			this.options.display = 0;

			this.fireEvent('onCloseStart', [this.Overlay]);

      this.Transition = new Fx.Tween(this.Overlay,
        {
          property: 'opacity',
          duration: this.options.closeDuration,
          transition: this.options.closeEffect,
          onComplete: function() {
              this.fireEvent('onCloseComplete', [this.Overlay]);
          }.bind(this)
        }
      ).start(0);

		}			
  
  },
  
  
	/*
	Property: replaceBox
    Cambiar de tamaño y posicionar el lightbox en el centro de la pantalla
	*/
	replaceBox: function(data) {
//		if(this.options.display == 1)
		{
        sizes = window.getSize();
        scrollito = window.getScroll();
        
        data = $extend({
          'width'  : this.ajustarWidth,
          'height' : this.ajustarHeight,
          'resize' : 0
        }, data || {});

        if(this.MoveBox)
          this.MoveBox.cancel();

        this.MoveBox = new Fx.Morph(this.Contenedor, {
          duration: this.options.moveDuration,
          transition: this.options.moveEffect
        }).start({
          'left': (scrollito.x + (sizes.x - data.width) / 2).toInt(),
          'top': (scrollito.y + (sizes.y - data.height) / 2).toInt()-40
        });


        if (data.resize) {
          if(this.ResizeBox2)
            this.ResizeBox2.cancel();
          this.ResizeBox2 = new Fx.Morph(this.Contenido, {
            duration: this.options.resizeDuration,
            transition: this.options.resizeEffect
          }).start({
            'height': data.height+ 'px'
          });

          if(this.ResizeBox)
            this.ResizeBox.cancel();

          this.ResizeBox = new Fx.Morph(this.Contenedor, {
            duration: this.options.resizeDuration,
            transition: this.options.resizeEffect
          }).start({
            'width': data.width + 'px'
          });
        }

		}

		this.Overlay.set('styles', {
				'height': '0px',
				'width': '0px'
    });

		this.Overlay.set('styles', {
				'height': window.getScrollHeight() + 'px',
				'width': window.getScrollWidth() + 'px'
    });
	},

	/*
	Function: getInfo
		Devolver los botones de navegacion
	*/	
  getInfo: function (image, id) {
      return new Element('a', {
      'id'    : this.options.name+id,
      'title' : image.title,
      'href'  : image.href,
      'rel'   : image.rel,
      'mywidth'  : image.getAttribute("mywidth"),
      'myheight'  : image.getAttribute("myheight")
      }).adopt(new Element('div', {
        'class' : 'navbt bt'+id+' navbt'+this.options.color+id
      }));
  },

  isImage: function(url) {
  	var baseURL = url.match(/(.+)\?/);
  	baseURL = baseURL ? baseURL[1] : url;
  	return baseURL.match(this.imageTypes);
  },
	/*
	Function: show
		Verificar que el enlace apunte hacia una imagen, y preparar el lightbox.
	*/	

  show: function(caption, url, rel, callerObj) {
      this.showLoading();

      // Si la imagen pertenece a un grupo
      this.MostrarNav = false;
      var imageCount = "";
      if (rel) {
          this.MostrarNav = true;
          /* START: TODO
		   *  optimize this process to only execute once and not everytime that a link is called
		   */
          this.imageGroup = [];
		  $$("a."+this.options.find).each(function(el){
              if (el.rel == rel) {
                  this.imageGroup[this.imageGroup.length] = el;
              }
          }.bind(this));
          /* END: TODO */

          var foundSelf = false;
          
          for (var i = 0; i < this.imageGroup.length; i++) {
              var image = this.imageGroup[i];
              if (image.href == url) {
                  foundSelf = true;
                  imageCount = "Image " + (i + 1) + " of " + (this.imageGroup.length);
              } else {
                  if (foundSelf) {
                      this.next = this.getInfo(image, "Right");
                      this.next.addEvent('click', function(event) {
                        event.stop();
                      }.bind(this));
                      // stop searching
                      break;
                  }
                  else {
                      // didn't find ourself yet, so this may be the one before ourself
                      this.prev = this.getInfo(image, "Left");
                      this.prev.addEvent('click', function(event) {
                        event.stop();
                      }.bind(this));
                  }
              }
          }
      }
      
    /**
     * Verificar si es una imagen:
     *****************************/
    if (this.isImage(url)) {
		this.showImage(caption, url, rel, callerObj);
    }
    else {
		this.showIFrame(caption, url, rel, callerObj);
    }
    
    this.showNav(caption);
    this.display(1);
  
  },
  
  showLoading: function() {
      // Mostrar LOADING....
      this.Contenido.innerHTML = '<div class="loading '+this.loadingBGClassName+'"></div>';
      this.Contenedor.className = this.contenedorClassName;
      this.Contenedor.setStyles({
	'background' : 'url()'
      });

      this.replaceBox({
        'width'  : this.options.BoxStyles['width'],
        'height' : this.options.BoxStyles['height'],
        'resize' : 1
      });
  },
  
  showImage: function(caption, url, rel, callerObj) {
  	/**
	* Cargar Imagen.
	*****************/
      	  this.imgPreloader = new Image();
      	  this.imgPreloader.onload = function() {
          this.imgPreloader.onload=function(){};

          var imageWidth = this.imgPreloader.width;
          var imageHeight = this.imgPreloader.height;
          
          // Ajustar el tamaño del lightbox
          if (this.MostrarNav || caption){
            this.ajustarHeight = (imageHeight-20);
          }else{
            this.ajustarHeight = (imageHeight-33);
          };

          this.ajustarWidth = (imageWidth+14);
          
          this.replaceBox({
            'width'  :this.ajustarWidth,
            'height' :this.ajustarHeight,
            'resize' : 1
          });
          
          // Mostrar la imagen, solo cuando la animacion de resizado se ha completado
          this.ResizeBox.addEvent('onComplete', function() {
          	
          	this.bt.className = eval('this.bt.className.replace(/('+this.strBG+'|'+this.strBGIFrame+')/g, "")') + " " + this.strBG;
     		this.bt_conner.className = eval('this.bt_conner.className.replace(/('+this.strBG+'|'+this.strBGIFrame+')/g, "")') + " " + this.strBG;
     		this.bb.className = eval('this.bb.className.replace(/('+this.strBG+'|'+this.strBGIFrame+')/g, "")') + " " + this.strBG;
     		this.innerbb.className = eval('this.innerbb.className.replace(/('+this.strBG+'|'+this.strBGIFrame+')/g, "")') + " " + this.strBG;
      		this.Contenido.className = this.contenidoClassName;
     		
     		this.Contenedor.className = this.contenedorClassName;
     		this.Contenido.innerHTML = "";
     		this.Contenedor.setStyles({
			'background' : 'url('+url+') no-repeat 7px 7px'
		});
		
	    }.bind(this));
	    this.addButtons();
         }.bind(this);
      
         this.imgPreloader.onerror = function() {
	      	this.Contenido.className = this.contenidoClassName;
		this.Contenido.innerHTML = "";
	     	
	     	this.Contenedor.className = this.contenedorClassName + " " + this.errorBGClassName;
		this.replaceBox({
		  'width'  : this.options.BoxStyles['width'],
		  'height' : this.options.BoxStyles['height'],
		  'resize' : 1
		});
		this.addButtons();
         }.bind(this);

         this.imgPreloader.src = url;
  },
  
  showIFrame: function(caption, url, rel, callerObj) {
  	this.imgPreloader = new Image();
        this.imgPreloader.onload = function() {};
        this.imgPreloader.onerror = function() {};
        this.imgPreloader.src = url;
        
        var imageWidth = parseInt(callerObj.getAttribute("mywidth"));
	var imageHeight = parseInt(callerObj.getAttribute("myheight"));
	
	if(!(imageWidth > 0)) imageWidth = 500;
	if(!(imageHeight > 0)) imageHeight = 500;

    	// Ajustar el tamaño del lightbox
	if (this.MostrarNav || caption){
		this.ajustarHeight = (imageHeight-20);
	}else{
		this.ajustarHeight = (imageHeight-33);
	};

	this.ajustarWidth = (imageWidth+14);

	this.replaceBox({
		'width'  :this.ajustarWidth,
		'height' :this.ajustarHeight,
		'resize' : 1
	});
	
	this.ResizeBox.addEvent('onComplete', function() {
		this.Contenido.className = this.contenidoClassName + " " + this.contenidoBGClassName;
     		this.bt.className = eval('this.bt.className.replace(/('+this.strBG+'|'+this.strBGIFrame+')/g, "")') + " " + this.strBGIFrame;
     		this.bt_conner.className = eval('this.bt_conner.className.replace(/('+this.strBG+'|'+this.strBGIFrame+')/g, "")') + " " + this.strBGIFrame;
     		this.bb.className = eval('this.bb.className.replace(/('+this.strBG+'|'+this.strBGIFrame+')/g, "")') + " " + this.strBGIFrame;
     		this.innerbb.className = eval('this.innerbb.className.replace(/('+this.strBG+'|'+this.strBGIFrame+')/g, "")') + " " + this.strBGIFrame;
     		this.Contenedor.className = this.contenedorClassName;
     		
     		var iframe_width = this.isMSIE ? "100%" : (this.Contenido.offsetWidth - 18) + "px";
		var iframe_height = this.isMSIE ? "100%" : (this.Contenido.offsetHeight - 5) + "px";
		this.Contenido.innerHTML = '<iframe src="'+url+'" style="width:'+iframe_width+'; height:'+iframe_height+'; border:0;" frameborder="0"></iframe>';
      	}.bind(this));
	this.addButtons();
  },
  
  addButtons: function(){
    if(this.prev) this.prev.addEvent('click', function(event) {event.stop();this.hook(this.prev);}.bind(this));
    if(this.next) this.next.addEvent('click', function(event) {event.stop();this.hook(this.next);}.bind(this));
  },
  
 /**
  * Mostrar navegacion.
  *****************/
  showNav: function(caption) {
    if (this.MostrarNav || (this.options.showCaption && caption)) {
      this.bb.addClass("bbnav");
      this.Nav.empty();
      this.Nav.injectInside(this.innerbb);
      
      this.Description.set('text', (this.options.showCaption ? caption : ""));
      
      this.Nav.adopt(this.prev);
      this.Nav.adopt(this.next);
      this.Description.injectInside(this.Nav);
    }
    else
    {
      this.bb.removeClass("bbnav");
      this.innerbb.empty();
    }
  }

  
});

SexyLightBox.implement(new Events, new Options);
