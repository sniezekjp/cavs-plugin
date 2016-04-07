(function() {

    tinymce.create('tinymce.plugins.Shortcodes', {

        init : function(ed, url) {
        },
        createControl : function(n, cm) {

            if(n=='Shortcodes'){
                var mlb = cm.createListBox('Shortcodes', {
                     title : 'Shortcodes',
                     onselect : function(v) {

                        if(v == 'Schedule'){
							
							content = '[table]<br />[columns names="Week, Tournament, Location, Sites"]<br />[row week="Dates" tournament="Name" location="City, State" sites="McLean HS"]<br />[row week="Dates" tournament="Name" location="City, State" sites="McLean HS"]<br />[/table]';
                            tinymce.execCommand('mceInsertContent', false, content);

                        }

                        if(v == 'Players'){

							content = '[table]<br />[columns names="#, Name, School, Class"]<br />[row data="#, Name, School, Class"]<br />[row data="#, Name, School, Class"]<br />[row data="#, Name, School, Class"]<br />[row data="#, Name, School, Class"]<br />[row data="#, Name, School, Class"][/table]';

                            tinymce.execCommand('mceInsertContent', false, content);

                        }
                        
                        if(v == 'Gallery'){
	                        content = '[gallery]'; 
	                        tinymce.execCommand('mceInsertContent', false, content);
                        }
                        
                        if(v == 'Table'){
                            content = '<table class="table"><tr><th>Col 1</th><th>Col 2</th><th>Col 3</th></tr><tr><td></td><td></td><td></td></tr></table>';
                            tinymce.execCommand('mceInsertContent', false, content);
                        }


                     }
                });


                // Add some menu items
                var my_shortcodes = ['Schedule','Players', 'Gallery', 'Table'];

                for(var i in my_shortcodes)
                    mlb.add(my_shortcodes[i],my_shortcodes[i]);

                return mlb;
            }
            return null;
        }


    });
    tinymce.PluginManager.add('Shortcodes', tinymce.plugins.Shortcodes);
})();