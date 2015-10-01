Widget_Calendar = {

    wraper: null,
    actual_month: null,
    actual_year: null,
    
    months: ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'],
    short_days: ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'],
    days: ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'],

    init: function() {
        Widget_Calendar.wraper = document.getElementById('calendar_wraper');
        Widget_Calendar.title = document.getElementById('calendar_header').getElementsByTagName('span');
        if(Widget_Calendar.title.length > 0) {
            Widget_Calendar.title = Widget_Calendar.title[0];
        }
        var links = document.getElementById('calendar_header').getElementsByTagName('a');
        for(i = 0; i < links.length; ++i) {
            if(links[i].className == 'prev') {
                links[i].onclick = Widget_Calendar.prev_month;
            }
            if(links[i].className == 'next') {
                links[i].onclick = Widget_Calendar.next_month;
            }
        }
        Widget_Calendar.actual_month = (new Date()).getMonth() + 1;
        Widget_Calendar.actual_year = (new Date()).getFullYear();
        
        Widget_Calendar.__insert_calendar();
    },
    
    next_month: function() {
        var date = new Date(Widget_Calendar.actual_year, Widget_Calendar.actual_month, 1);
        Widget_Calendar.actual_month = date.getMonth() + 1;
        Widget_Calendar.actual_year = date.getFullYear();
        Widget_Calendar.__insert_calendar();
        return false;
    },
    
    prev_month: function() {
        var date = new Date(Widget_Calendar.actual_year, Widget_Calendar.actual_month - 2, 1);
        Widget_Calendar.actual_month = date.getMonth() + 1;
        Widget_Calendar.actual_year = date.getFullYear();
        Widget_Calendar.__insert_calendar();
        return false;
    },
    
    __generate_header: function() {
        var text = '<tr>';
        for(var i = 0; i < Widget_Calendar.short_days.length; ++i) {
            text += '<th>' + Widget_Calendar.short_days[i] + '</th>';
        }
        text += '</tr>';
        return text;
    },
    
    __insert_calendar: function() {
        Widget_Calendar.wraper.innerHTML = Widget_Calendar.__generate_table();
        Widget_Calendar.title.innerHTML = Widget_Calendar.months[Widget_Calendar.actual_month - 1] + ' ' + Widget_Calendar.actual_year;
    },
    
    __generate_table: function() {
        return '<table>' + Widget_Calendar.__generate_header() + Widget_Calendar.__generate_body() + '</table>';
    },
    
    __generate_body: function() {
        var today = new Date();
        var text = '';
        var first = Widget_Calendar.__first_day_of_week(Widget_Calendar.actual_month, Widget_Calendar.actual_year);
        var last = Widget_Calendar.__last_day_of_week(Widget_Calendar.actual_month, Widget_Calendar.actual_year);
        while(first <= last) {
            if(first.getDay() === 1) {
                text += '<tr>';
            }
            if(first.getMonth() == Widget_Calendar.actual_month - 1) {
                if(first.toDateString() == today.toDateString()) {
                    text += '<td class="today">';
                } else {
                    text += '<td>';
                }
            } else {
                text += '<td class="other">';
            }
            text += first.getDate() + '</td>';
            first = first.add_day();
            if(first.getDay() === 1) {
                text += '</tr>';
            }
            
        }
        return text;
    },
    
    __first_day_of_month: function(month, year) {
        var date = new Date(year, month - 1, 1);
        var day = date.getDay();
        return day ? day : 7;
    },
    
    __last_day_of_month: function(month, year) {
        var date = new Date(year, month, 0);
        var day = date.getDay();
        return day ? day : 7;
    },
    
    __first_day_of_week: function(month, year) {
        var day = Widget_Calendar.__first_day_of_month(month, year);
        var date = new Date(year, month - 1, 1 + 1 - day);
        return date;
    },
    
    __last_day_of_week: function(month, year) {
        var day = Widget_Calendar.__last_day_of_month(month, year);
        var date = new Date(year, month, 0 + 7 - day);
        return date;
    }

};

Date.prototype.add_day = function() {
    return new Date(this.getFullYear(), this.getMonth(), this.getDate() + 1);
};

Widget_Calendar.init();