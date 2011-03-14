Ext.override(Ext.ux.form.MultiSelect, {
	onRender: function(ct, position){
    	Ext.ux.form.MultiSelect.superclass.onRender.call(this, ct, position);

    	var fs = this.fs = new Ext.form.FieldSet({
    		renderTo: this.el,
    		autoScroll: true,
    		title: this.legend,
    		height: this.height,
    		width: this.width,
    		style: "padding:0;",
    		tbar: this.tbar,
    		fbar: this.fbar
    	});
    	fs.body.addClass('ux-mselect');

    	this.view = new Ext.list.ListView({
    		multiSelect: true,
    		store: this.store,
    		columns: [{ header: 'Value', width: 1, dataIndex: this.displayField, tpl: this.tpl}],
    		hideHeaders: true
    	});

    	fs.add(this.view);

    	this.view.on('click', this.onViewClick, this);
    	this.view.on('beforeclick', this.onViewBeforeClick, this);
    	this.view.on('dblclick', this.onViewDblClick, this);

    	this.hiddenName = this.name || Ext.id();
    	var hiddenTag = { tag: "input", type: "hidden", value: "", name: this.hiddenName };
    	this.hiddenField = this.el.createChild(hiddenTag);
    	this.hiddenField.dom.disabled = this.hiddenName != this.name;
    	fs.doLayout();
	},
	
	afterRender: function() {
        Ext.ux.form.MultiSelect.superclass.afterRender.call(this);

        if (this.ddReorder && !this.dragGroup && !this.dropGroup){
            this.dragGroup = this.dropGroup = 'MultiselectDD-' + Ext.id();
        }

        if (this.draggable || this.dragGroup){
            this.dragZone = new Ext.ux.form.MultiSelect.DragZone(this, {
                ddGroup: this.dragGroup
            });
        }
        if (this.droppable || this.dropGroup){
            this.dropZone = new Ext.ux.form.MultiSelect.DropZone(this, {
                ddGroup: this.dropGroup
            });
        }
        
        this.loadMask = new Ext.LoadMask(this.getEl(), Ext.apply({store:this.store}, this.loadMask));
    },
    
    validateValue: function() {
    	
    }
});