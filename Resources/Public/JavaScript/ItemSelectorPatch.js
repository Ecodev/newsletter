/**
 * fixes an issue, that causes the itemselector not to load the data comin from the
 * record, that is loaded into the form
 * 
 * initial source:
 * http://www.extjs.com/forum/showthread.php?t=73302
 * big thanks to sormy ;)
 */
Ext.override( Ext.ux.form.ItemSelector, {
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

    getValue : function() {
        return this.hiddenField.value;
    },

    getValueFieldFromObject: function(valueField,object) {
    	var call = 'object.' + valueField;
    	return eval(call);
    }
});