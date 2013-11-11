/**
 * fixes an issue, that causes the itemselector not to load the data comin from the
 * record, that is loaded into the form
 * 
 * initial source:
 * http://www.extjs.com/forum/showthread.php?t=73302
 * big thanks to sormy ;)
 */
Ext.override( Ext.ux.form.ItemSelector, {
	/**
     * @cfg {Number} minSelections Minimum number of selections allowed (defaults to 0).
     */
    minSelections:0,
    /**
     * @cfg {Number} maxSelections Maximum number of selections allowed (defaults to Number.MAX_VALUE).
     */
    maxSelections:Number.MAX_VALUE,
    /**
     * @cfg {String} minSelectionsText Validation message displayed when {@link #minSelections} is not met (defaults to 'Minimum {0}
     * item(s) required').  The {0} token will be replaced by the value of {@link #minSelections}.
     */
    minSelectionsText:'Minimum {0} item(s) required',
    /**
     * @cfg {String} maxSelectionsText Validation message displayed when {@link #maxSelections} is not met (defaults to 'Maximum {0}
     * item(s) allowed').  The {0} token will be replaced by the value of {@link #maxSelections}.
     */
    maxSelectionsText:'Maximum {0} item(s) allowed',
    /**
     * @cfg {String} blankText Default text displayed when the control contains no items (defaults to the same value as
     * {@link Ext.form.TextField#blankText}.
     */
    blankText:Ext.form.TextField.prototype.blankText,
    /**
     * 
     */
    initComponent: function() {
    	Ext.ux.form.ItemSelector.superclass.initComponent.call(this);
    	this.addEvents({
    		'rowdblclick' : true,
    		'change' : true
    	});
	},
    /**
     * Sets the value for the field
     */
    setValue: function(val) {
        this.reset();
        if (val == null) return;
        val = val instanceof Array ? val : val.split(this.delimiter);
        var rec, i, id;
        for (i = 0; i < val.length; i++) {
            var vf = this.fromMultiselect.valueField;
            var object = val[i];
            var objectId = this.getValueFieldFromObject(vf,object);
            var idx = this.toMultiselect.view.store.findBy(function(record){
                return record.data[vf] == objectId;
            });
            if (idx != -1) continue;            
            var idx = this.fromMultiselect.view.store.findBy(function(record){
                return record.data[vf] == objectId;
            });
            var rec = this.fromMultiselect.view.store.getAt(idx);
            if (rec) {
                this.toMultiselect.view.store.add(rec);
                this.fromMultiselect.view.store.remove(rec);
            }
        }
    },
    /**
     * 
     */
    valueChanged: function(store) {
        var record = null;
        var values = [];
        for (var i=0; i<store.getCount(); i++) {
            record = store.getAt(i);
            values[i] = record.data;
        }
        this.hiddenField.value = values;
        this.fireEvent('change', this, this.getValue(), this.hiddenField.value);
    },
    /**
     * 
     */
    getValue : function() {
        return this.hiddenField.value;
    },
    /**
     * 
     */
    getValueFieldFromObject: function(valueField,object) {
    	var call = 'object.' + valueField;
    	return eval(call);
    },
    /**
     * 
     */
    validate: function() {
    	return this.validateValue(this.hiddenField.value);
    },
    /**
     * 
     */
    validateValue: function(value) {
        this.clearInvalid();
    	if (value.length < 1) { // if it has no value
            if (this.allowBlank) {
                return true;
            } else {
                this.markInvalid(this.blankText);
                return false;
            }
       }
       if (value.length < this.minSelections) {
           this.markInvalid(String.format(this.minSelectionsText, this.minSelections));
           return false;
       }
       if (value.length > this.maxSelections) {
           this.markInvalid(String.format(this.maxSelectionsText, this.maxSelections));
           return false;
       }
       return true;
    }
});