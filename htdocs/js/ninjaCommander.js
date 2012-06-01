/**
 * NinjaCommander:
 * Handles and sets up all the coms' whilst out in the field.
 * Delegates communications to NinjaComs'
 * pageActions to ninjaAction
 * and Form battles to ninjaForm.
 */

NinjaCommander = Class.create({

    /**
     * Loads all the javascript behaviours.
     */
    initialize: function(){
        this.startForms();
        this.startEditors();
        this.startUpdaters();
        this.startActions();
    },

    startEditors: function(){
        var editors = $$('.tinymce');
        if(editors.size() > 0)
        {
            if(typeof tinyMCE != 'object')
            {
                window.findAndLoadMce = function(){
                    tinyMCE.settings = {
                      theme: 'advanced',
                      theme_advanced_buttons1: 'bold,underline,strikethrough,blockquote,|,link,unlink',
                      theme_advanced_buttons2 : "",
                      theme_advanced_buttons3 : "",
                      theme_advanced_toolbar_location: 'top',
                      theme_advanced_toolbar_align: 'left',
                      valid_elements: 'a[href],strong/b,strong,ol,ul,li,blockquote,p,br',
                      paste_auto_cleanup_on_paste: true,
                      paste_remove_spans: true,
                      paste_remove_styles: true,
                      verify_html: true,
                      debug: true,
                      plugins: 'paste',
                      setup : function(ed){
                        if(false === $(ed.editorId).hasClassName("large"))
                        {
                          ed.onKeyUp.add(function(ed, e) {
                            var length = (tinyMCE.activeEditor.getContent()).replace(/(<([^>]+)>)/ig,"").length;
                            var text =  length + " Zeichen";
                            var wordCount = $('wordCount_' + ed.id );
                            if(!wordCount)
                            {
                                var toolbar = $(tinyMCE.activeEditor.id + '_parent').down('.mceToolbar');
                                wordCount = $(new Element('span', {'id': 'wordCount_' + ed.id , 'style' : 'font-size:12px;float:right; padding:5px 15px;'}));
                                toolbar.insert({top: wordCount});
                            }
                            wordCount.update(text);
                            wordCount.setStyle(length >= 550 ? ' color:red;' :'color:green;');
                          });
                        }
                        ed.onInit.add(function(ed,e){
                          tinyMCE.editors[ed.id].load();
                        });
                     }
                   };

                   $$('.tinymce').each(function(el){
                       el.identify();
                       if( undefined === tinyMCE.get(el.id)){
                         tinyMCE.execCommand('mceAddControl', true,  el.id );
                         if(el.hasClassName("large"))
                         {
                          tinyMCE.get(el.id).remove("KeyUp");
                         }
                       }
                       if(-1 !== $A(tinyMCE.editors).indexOf(el.id))
                       {
                         tinyMCE.editors[el.id].load();
                       }
                   });
                };

                $$('head')[0].insert(new Element('script',{'src':"js/tiny_mce/tiny_mce.js?ver=1"}));
                return;
            }

          window.findAndLoadMce();
          return;

       }
       document.observe('lightview:loaded', function() { NinjaCommander.initalize(); });
    },


    /**
     * Creates behaviours for the actions like storyclick.
     */
    startActions: function(){
        // Setup the ninjaActions
        $$('a.ninjaAction').each(function(el){
            if (el.id) {

                // do not reassign
                if (el.ninjaAction) {
                    return;
                }

                el.ninjaAction = NinjaAction;

                // Add event listeners for ninja Actions
                el.observe("click", function(e){
                    this.ninjaAction.executeAction(e);
                    Event.stop(e);
                }.bind(el));
            }
        });
    },
    /**
     * Adds behaviour for ninjaForms.
     */
    startForms: function(){

        // Setup the ninjaValidation
        $$('form.ninjaForm').each(
          function(el)
          {
            if (el.id && typeof el.ninjaValidator != 'object')
            {
                // Add the validator to the DOM element for use of this in context.
                el.ninjaValidator = NinjaValidator;
                el.ninjaValidator.formName = el.id;
                token = $(el.id + '__csrf_token');
                if (token) {
                    el.ninjaValidator.csrfToken = $F(token);
                }

                // Setup the event listeners for required attributes
                el.requiredElements = $$("#" + el.id + ' .ninjaRequired');
                if (el.requiredElements) {
                    el.requiredElements.invoke("observe", "blur", function(e){
                        this.ninjaValidator.requiredCheck(e);
                    }.bind(el));
                }

                // Setup the event listeners for validate attributes
                el.validationElements = $$("#" + el.id + ' .ninjaValidate');
                if (el.validationElements) {
                    el.validationElements.invoke("observe", "blur", function(e){
                        this.ninjaValidator.validateField(e);
                    }.bind(el));
                }

                // Setup the event listeners for form submit element.
                el.observe("submit", function(e)
                {
                  if(typeof tinyMCE === "object")
                  {
                    tinyMCE.triggerSave();
                  }
                  this.ninjaValidator.preSubmitCheck(e);
                }.bind(el));
            }
            el.enable();
        });
    },

    /**
     * create updater for any element (a,form) with classname ninjaUpdater
     * may create link updaters, form updaters and filters (which needs additional
     * classname ninjaFilter).
     *
     * also there might be some options like ninjaConfirm (shows confirm-alert before action)
     * or ninjaCallback[Xyz] to act as callback, this is loaded from /js/ninjaCallback[Xyz].js
     * automatically, the target for the callback is set by adding the target id as
     * classname to the updater element, otherwise element will be replaced by result
     *
     * example: <form action="ajax/me" class="ninjaUpdater ninjaForm ninjaFilter targetDivId">
     *       <link href="ajax/me" class="ninjaUpdater ninjaConfirm targetDivId">update</a>
     *       <form action="ajax/me" class="ninjaUpdater ninjaForm targetDivId">
     */
    startUpdaters: function(){
        $$(".ninjaUpdater").each(function(el){
            if (!el.ninjaUpdater) {
                el.ninjaUpdater = new NinjaUpdater(el);
            }
        });
    }
});
