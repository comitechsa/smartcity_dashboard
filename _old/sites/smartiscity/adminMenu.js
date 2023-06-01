
	Ext.onReady(function(){
		 //Ext.InfoWindow.msg('Done', 'Your fake items were loaded! 2',2);
	}, this);
	

	MyDesktop = new Ext.app.App({
		init :function(){
			Ext.QuickTips.init();
		},
	
		getModules : function(){
			return [
				new MyDesktop.Main1()
				//, new MyDesktop.Main4()
			];
		},
	
		// config for the start menu
		getStartConfig : function(){
			return {
				title: 'Administrator',
				iconCls: 'user',
				toolItems: [ 
							/*{text:'Αναφορές',iconCls:'settings', src: "index.php?com=reports", handler: clickHandler}
							, '-',*/
							 {text:'Χρήστες',iconCls:'user', src: "index.php?com=users2", handler: clickHandler}
							 ,{text:'Διαχειριστές',iconCls:'user', src: "index.php?com=users", handler: clickHandler}
							, '-'
							, {text:'Έξοδος',iconCls:'logout',handler: function(){ window.location.href="index.php?logout=true";},scope:this}
						]
				};
		 }
	});
	
	// for mutiple window
	var windowIndex = 0;
	function clickHandler(item, e) {
		windowIndex++;
		var win_id = 'Window_'+ windowIndex;
		var desktop = MyDesktop.getDesktop();
		var win = desktop.getWindow(win_id);
		if(!win){
			win = desktop.createWindow({
				id: win_id,
				title: item.text,
				width:640,
				height:480,
				html : '<iframe src="' + item.src + '" width="100%" height="100%" frameborder="no" id="If_'+win_id+'"></iframe>',
				//autoLoad : { url: 'test.php?id='+win_id, scripts: true  },
				iconCls: 'bogus',
				shim:false,
				animCollapse:false,
				constrainHeader:true
			});
		}
		win.show();
	}
	
	MyDesktop.Main1 = Ext.extend(Ext.app.Module, {
		init : function(){
			this.launcher = {
				text: 'Ιστοσελίδα',
				iconCls: 'bogus',
				handler: function() { return false; },
				menu: {               
					items:[
						//{text: 'Σελίδες',iconCls:'bogus', src: "index.php?com=pages", handler: clickHandler}
						{text: 'Αρχική Σελίδα',iconCls:'bogus', src: "index.php?com=pageone&item=1", handler: clickHandler}
						,{text: 'Όροι Χρήσης',iconCls:'bogus', src: "index.php?com=pageone&item=4", handler: clickHandler}
						,{text: 'Στοιχεία Επικοινωνίας',iconCls:'bogus', src: "index.php?com=pageone&item=3", handler: clickHandler}
					]
				}
			}
		}
	});
	
	/*
	
	MyDesktop.Main4 = Ext.extend(Ext.app.Module, {
		init : function(){
			this.launcher = {
				text: 'Newsletters',
				iconCls: 'bogus',
				handler: function() { return false; },
				menu: {               
					items:[
						{text: 'Αρχείο Newsletters',iconCls:'bogus', src: "index.php?com=newslettersA", handler: clickHandler}
						,{text: 'Αποδέκτες Newsletters',iconCls:'bogus', src: "index.php?com=newslettersR", handler: clickHandler}
						,{text: 'Ομάδες Newsletters',iconCls:'bogus', src: "index.php?com=newslettersG", handler: clickHandler}
					]
				}
			}
		}
	});*/