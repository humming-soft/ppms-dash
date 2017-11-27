/*Created : Sebin Thomas
For     : Backbone Constructors, Views and the associated functions
Date    : 14/07/2016*/

//Redefined Console.log function
//Please comment the below line when console.log requires.
//console.log = function() {}

mpxd.modules.ppms = {}
mpxd.constructors.padu_issuemitigation = function(data) {
    var el = "#portlet_" + data.id;
    return new mpxd.modules.ppms.issuemitigation({data: data, el: el});
}
mpxd.constructors.padu_latetask = function(data) {
    var el = "#portlet_" + data.id;
    return new mpxd.modules.ppms.latetask({data: data, el: el});
}
mpxd.constructors.padu_upcomingtask = function(data) {
    var el = "#portlet_" + data.id;
    return new mpxd.modules.ppms.upcomingtask({data: data, el: el});
}
mpxd.constructors.padu_scurve = function(data) {
    var el = "#portlet_" + data.id;
    return new mpxd.modules.ppms.scurve({data: data, el: el});
}
mpxd.constructors.padu_projectcost = function(data) {
    var el = "#portlet_" + data.id;
    return new mpxd.modules.ppms.projectcost({data: data, el: el});
}
mpxd.constructors.padu_progress = function(data) {
    var el = "#portlet_" + data.id;
    return new mpxd.modules.ppms.progress({data: data, el: el});
}
mpxd.constructors.padu_wbs = function(data) {
    var el = "#portlet_" + data.id;
    return new mpxd.modules.ppms.wbs({data: data, el: el});
}
mpxd.constructors.padu_parking = function(data) {
    var el = "#portlet_" + data.id;
    return new mpxd.modules.ppms.parking({data: data, el: el});
}
mpxd.modules.ppms.progress = Backbone.View.extend({
    initialize: function (options) {
        this.data = options.data;
        this.render();
    }, render: function () {
        var that = this;
        var html = mpxd.getTemplate(that.data.type);
        template = _.template(html, {data: that.data});
        that.$el.html(template);
        that.$el.find('.portlet_content').css({"height":(that.$el.find('.content').parent().parent().parent().height())-40});
        //that.$el.find('.portlet_content').mCustomScrollbar({theme:"dark-3"});
    }
});
mpxd.modules.ppms.projectcost = Backbone.View.extend({
    initialize: function (options) {
        this.data = options.data;
        this.render();
    }, render: function () {
        var that = this;
        var html = mpxd.getTemplate(that.data.type);
        template = _.template(html, {data: that.data});
        that.$el.html(template);
        that.$el.find('.portlet_content').css({"height":(that.$el.find('.content').parent().parent().parent().height())-40});
        that.$el.find('.portlet_content').mCustomScrollbar({theme:"dark-3"});

        var projectcost_earned = parseFloat((typeof that.data.data.earned == "undefined")?0:that.data.data.earned);
        var projectcost_balance = parseFloat((typeof that.data.data.balance == "undefined")?0:that.data.data.balance);

        Highcharts.chart('portlet_padu_pcu', {
            chart: {
                type: 'bar'
            },
            title: {
                text: ''
            },
            xAxis: {
                categories: ['']
            },
            yAxis: {
                min: 0,
                max:100,
                title: {
                    text: 'Percentage (%)',
                    align: 'high'
                }
            },
            tooltip: {
                formatter: function() {
                    return this.series.name+' : <b>'+ this.point.y +'%</b>';
                }
            },
            plotOptions: {
                series: {
                    stacking: 'top'
                }
            },
            series: [{
                name: 'Balance Value',
                color:'#f50',
                data: [
                    { y : projectcost_earned, myData : '' }
                ]
            }, {
                name: 'Earned Value',
                color:'#0f5',
                data: [
                    { y : projectcost_balance, myData : '' }
                ]
            }],
            credits: {
                enabled: false
            }
        });
    }
});
mpxd.modules.ppms.scurve = Backbone.View.extend({
    initialize: function (options) {
        this.data = options.data;
        this.render();
    }, render: function () {
        var that = this;
        var html = mpxd.getTemplate(that.data.type);
        template = _.template(html, {data: that.data});
        that.$el.html(template);
        that.$el.find('.portlet_content').css({"height":(that.$el.find('.content').parent().parent().parent().height())-40});
        that.$el.find('.portlet_content').mCustomScrollbar({theme:"dark-3"});
    }
});
mpxd.modules.ppms.upcomingtask = Backbone.View.extend({
    initialize: function (options) {
        this.data = options.data;
        this.render();
    }, render: function () {
        var that = this;
        var html = mpxd.getTemplate(that.data.type);
        template = _.template(html, {data: that.data});
        that.$el.html(template);
        that.$el.find('.portlet_content').css({"height":(that.$el.find('.content').parent().parent().parent().height())-40});
        that.$el.find('.portlet_content').mCustomScrollbar({theme:"dark-3"});
    }
});
mpxd.modules.ppms.latetask = Backbone.View.extend({
    initialize: function (options) {
        this.data = options.data;
        this.render();
    }, render: function () {
        var that = this;
        var html = mpxd.getTemplate(that.data.type);
        template = _.template(html, {data: that.data});
        that.$el.html(template);
        that.$el.find('.portlet_content').css({"height":(that.$el.find('.content').parent().parent().parent().height())-40});
        that.$el.find('.portlet_content').mCustomScrollbar({theme:"dark-3"});
    }
});
mpxd.modules.ppms.issuemitigation = Backbone.View.extend({
    initialize: function (options) {
        this.data = options.data;
        this.render();
    }, render: function () {
        var that = this;
        var html = mpxd.getTemplate(that.data.type);
        template = _.template(html, {data: that.data});
        that.$el.html(template);
        that.$el.find('.portlet_content').css({"height":(that.$el.find('.content').parent().parent().parent().height())-40});
        that.$el.find('.portlet_content').mCustomScrollbar({theme:"dark-3"});
    }
});
mpxd.modules.ppms.parking = Backbone.View.extend({
    initialize: function (options) {
        this.data = options.data;
        this.render();
    }, render: function () {
        var that = this;
        var html = mpxd.getTemplate(that.data.type);
        template = _.template(html, {data: that.data});
        that.$el.html(template);
        that.$el.find('.portlet_content').css({"height":(that.$el.find('.content').parent().parent().parent().height())-40});
        that.$el.find('.portlet_content').mCustomScrollbar({theme:"dark-3"});
    }
});
mpxd.modules.ppms.wbs = Backbone.View.extend({
    initialize: function (options) {
        this.data = options.data;
        this.render();
    }, render: function () {
        var that = this;
        var html = mpxd.getTemplate(that.data.type);
        template = _.template(html, {data: that.data});
        that.$el.html(template);
        that.$el.find('.portlet_content').css({"height":(that.$el.find('.content').parent().parent().parent().height())-40});
        that.$el.find('.portlet_content').mCustomScrollbar({theme:"dark-3"});

        var padu_wbs_sub = that.data.data.wbs_sub;
        var padu_wbs_num =  that.data.data.wbs_num;

        Highcharts.chart('portlet_padu_wbs', {
            chart: {
                type: 'column'
            },
            title: {
                text: ''
            },
            xAxis: {
                categories: padu_wbs_sub
            },
            yAxis: {
                min: 0,
                max: 100,
                title: {
                    text: 'Progress (%)'
                }
            },
            legend: {
                layout: 'vertical',
                align: 'right',
                verticalAlign: 'middle'
            },
            tooltip: {
                backgroundColor: '#445',
                formatter: function() {
                    return this.series.name+' : <b>'+ this.point.y +'%</b>';
                }
            },
            plotOptions: {
                column: {
                    stacking: 'top'
                }
            },
            series: [{
                name: 'Progress',
                color:'#77f',
                data: padu_wbs_num
            }],
            credits: {
                enabled: false
            }
        });
    }
});