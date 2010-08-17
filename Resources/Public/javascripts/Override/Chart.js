/**
 * Fix bug when using charts with tab panel. Message error: this.swf.setDataprovider is not a function
 * @link http://www.sencha.com/forum/showthread.php?78788-OPEN-197-3.0.0-svn-5208-this.swf.setDataprovider-is-not-a-function&p=449792#post449792
 *
 */
Ext.override(Ext.chart.Chart, {
	refresh : function(){
		if(this.fireEvent('beforerefresh', this) !== false){
            var styleChanged = false;
            // convert the store data into something YUI charts can understand
            var data = [], rs = this.store.data.items;
            for(var j = 0, len = rs.length; j < len; j++){
                data[j] = rs[j].data;
            }
            //make a copy of the series definitions so that we aren't
            //editing them directly.
            var dataProvider = [];
            var seriesCount = 0;
            var currentSeries = null;
            var i = 0;
            if(this.series){
                seriesCount = this.series.length;
                for(i = 0; i < seriesCount; i++){
                    currentSeries = this.series[i];
                    var clonedSeries = {};
                    for(var prop in currentSeries){
                        if(prop == "style" && currentSeries.style !== null){
                            clonedSeries.style = Ext.encode(currentSeries.style);
                            styleChanged = true;
                            //we don't want to modify the styles again next time
                            //so null out the style property.
                            // this causes issues
                            // currentSeries.style = null;
                        } else{
                            clonedSeries[prop] = currentSeries[prop];
                        }
                    }
                    dataProvider.push(clonedSeries);
                }
            }

            if(seriesCount > 0){
                for(i = 0; i < seriesCount; i++){
                    currentSeries = dataProvider[i];
                    if(!currentSeries.type){
                        currentSeries.type = this.type;
                    }
                    currentSeries.dataProvider = data;
                }
            } else{
                dataProvider.push({type: this.type, dataProvider: data});
            }

			//    this.swf.setDataProvider(dataProvider);
			if(this.swf && this.swf.setDataProvider) {
				this.swf.setDataProvider(dataProvider);
				if(this.seriesStyles){
					this.setSeriesStyles(this.seriesStyles);
				}
			}
            this.fireEvent('refresh', this);
        }
	}
});