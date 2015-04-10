(function() {
	function create_icons_listbox(){
		var icons=[
"no","activity","anchor","aside","attachment","audio","bold","book","bug","cart","category","chat","checkmark","close","close-alt","cloud","cloud-download","cloud-upload","code","codepen","cog","collapse","comment","day","digg","document","dot","downarrow","download","draggable","dribbble","dropbox","dropdown","dropdown-left","edit","ellipsis","expand","external","facebook","facebook-alt","fastforward","feed","flag","flickr","foursquare","fullscreen","gallery","github","googleplus","googleplus-alt","handset","heart","help","hide","hierarchy","home","image","info","instagram","italic","key","leftarrow","link","linkedin","linkedin-alt","location","lock","mail","maximize","menu","microphone","minimize","minus","month","move","next","notice","paintbrush","path","pause","phone","picture","pinned","pinterest","pinterest-alt","play","plugin","plus","pocket","polldaddy","portfolio","previous","print","quote","rating-empty","rating-full","rating-half","reddit","refresh","reply","reply-alt","reply-single","rewind","rightarrow","search","send-to-phone","send-to-tablet","share","show","shuffle","sitemap","skip-ahead","skip-back","skype","spam","spotify","standard","star","status","stop","stumbleupon","subscribe","subscribed","summary","tablet","tag","time","top","trash","tumblr","twitch","twitter","unapprove","unsubscribe","unzoom","uparrow","user","video","videocamera","vimeo","warning","website","week","wordpress","xpost","youtube","zoom ", 
];
		var list=[];
		for(var i=0; i<icons.length; i++){			
			list[i]={text:icons[i], value:icons[i]};
		}
		return list;
	};
	tinymce.create('tinymce.plugins.wpi_designer_button', {      
        init : function(ed, url) {		

			var args={label:"Default Button", fields:["text","link","target"]};
			var des_but=designer_button(ed, args);
			var args={label:"Button with Style", fields:["text","link","style_id","target"]};
			var des_but_style=designer_button(ed, args);
			var args={label:"Button with Style & Icon", fields:["text","link","style_id","icon","target"]};
			var des_but_icon=designer_button(ed, args);
			var args={label:"Insert Created Button", fields:["id"]};
			var des_but_id=designer_button(ed, args);			
 			 ed.addButton('wpi', {
				text: 'WPi',   
				icon : 'designer_button',
				image : url + '/editor_button.png',
				type: 'menubutton',
				menu:[
					{
						text:"WPi Designer Button", 
						menu:[
						  des_but, 
						  des_but_style, 
						  des_but_icon, 
						  des_but_id, 
						]
					},
				],
            });
			 ed.addCommand('designer_button', function() {
                var selected_text = ed.selection.getContent();
                var return_text = '';
                return_text = "[wpi_designer_button text='' link='' icon='']" + selected_text;
                ed.execCommand('mceInsertContent', 0, return_text);
            });
        },
        createControl : function(n, cm) {
            return null;
        }, 
        getInfo : function() {
            return {
                longname : 'WPi Designer Button',
                author : 'WooPrali',
                authorurl : 'http://wooprali.prali.in',
                infourl : 'http://wooprali.prali.in/plugins/wpi-designer-button-shortcode',
                version : "1.0.0"
            };
        }
    });   
    tinymce.PluginManager.add( 'wpi_designer_button', tinymce.plugins.wpi_designer_button );
	
	function create_fields(args){		

		var return_fields=[];
		var fields={
			id:{
				label:'ID',
				type:'textbox',
				name: 'id'
			},
			text:{
				label:'Text',
				type:'textbox',
				name: 'text'
			},
			style_id:{
				label:'Style Id',
				type:'textbox',
				name: 'style_id'
			},
			icon:{
				label:'Icon',
				type:'listbox',
				name: 'icon',
				values: create_icons_listbox(),
			},
			link:{
				label:'Link',
				type:'textbox',
				name: 'link'
			},
			target:{
				label:'Target',
				type:'listbox',
				name: 'target',
				values:[
					{
						text:"Self",
						value:"self",
					},
					{
						text:"New Window",
						value:"_blank",
					},
				]
			}
		};
		var info={
			label:'Help',
			type:'textbox',
			name:'help',
			value:'"Creating New Button:" In main menu, Go to WPi > Button and click on (Add New) button  for creating new button. "Creating New Style:"  In main menu, Go to WPi > Styles and click on (Add New) button for creating new style.',
			multiline: true,
			minWidth: 300,
			minHeight: 100,
			disabled:true,
	
		}
		var arg_fields=args['fields'];
		var count=0;
		for (var i=0; i< arg_fields.length; i++){ 			 
			if (fields[arg_fields[i]]!=undefined){
				return_fields[count++]=fields[arg_fields[i]];
			}
		}
		return_fields[count++]=info;
		
		return return_fields;
	};
	function return_text(ed, e, args){
		var fields={
			id:"id='"+e.data.id+"'",
			text:"text='"+e.data.text+"'",
			style_id:"style_id='"+e.data.style_id+"'",	
			icon:"icon='"+e.data.icon+"'",
			link:"link='"+e.data.link+"'",
			target:"target='"+e.data.target+"'",
		};			
		var data="";
		var arg_fields=args['fields'];		
		for (var i=0; i< arg_fields.length; i++){ 			 
			if (fields[arg_fields[i]]!=undefined){
				data+=" "+fields[arg_fields[i]];
			}
		}		
		var selected_text = ed.selection.getContent();	
		var output = "[wpi_designer_button "+data+"]" + selected_text;
		return output;
	};
	function designer_button(ed,args){
		var fields=create_fields(args);		
		var button={
			text:args['label'],
			onclick: function (){				
				ed.windowManager.open({
					title:args['label'],
					body: fields,
					onsubmit: function(e){
						var r_text=return_text(ed, e, args);						
						ed.insertContent(r_text);	
					},
				});
			},
		  }
		return button;
	};
    
})();