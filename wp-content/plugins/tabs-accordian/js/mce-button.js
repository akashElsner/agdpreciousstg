(function() {
    tinymce.PluginManager.add('editor_ta_buttons', function( editor, url ) {
        editor.addButton( 'accorion_button', {
            text: 'ACCORDION',
			type: 'menubutton',
            icon: false,
			menu: [
                {
                    text: 'Add New Accordion',
                    value: '[accordion_wrap]<br>[accordion_panel title="Title"]...[/accordion_panel]<br>[/accordion_wrap]<br>',
                    onclick: function() {
                        editor.insertContent(this.value());
                    }
                },
				{
                    text: 'Add New Panel',
                    value: '<br>[accordion_panel title="Title"]...[/accordion_panel]<br>',
                    onclick: function() {
                        editor.insertContent(this.value());
                    }
                }
			]
        });
		
		editor.addButton( 'tabs_button', {
            text: 'TABS',
			type: 'menubutton',
            icon: false,
			menu: [
                {
                    text: 'Add New Tabbed Section',
                    value: '[tabs_wrap]<br>[tab_panel title="Title"]...[/tab_panel]<br>[/tabs_wrap]<br>',
                    onclick: function() {
                        editor.insertContent(this.value());
                    }
                },
				{
                    text: 'Add New Tab Panel',
                    value: '<br>[tab_panel title="Title"]...[/tab_panel]<br>',
                    onclick: function() {
                        editor.insertContent(this.value());
                    }
                }
			]
        });
    });
})();

