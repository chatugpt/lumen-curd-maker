/**
 * Created by Administrator on 2017-07-31.
 */

    /*
     *
     * $.getLunar(time) 返回以下值
     nMonth      //农历月
     nDays      //农历日
     yYear      //阳历年
     yMonth      //阳历月
     yDays      //阳历日
     cWeek      //汉字星期几
     nWeek      //数字星期几
     * */

    function setTime() {
        $("#date03 ,#created_at, #updated_at ,#dd168 ,#dd169 ,#dd170").jeDate({
            isinitVal:true,
            isClear:false,
//       festival:true,
            ishmsVal:true,
            // ishmsVal:true,
            minDate: '2016-06-16',
            // maxDate: $.nowDate({DD:0}),
            format:"YYYY-MM-DD hh:mm:ss",
            zIndex:3000,

        })
        $("#date05").jeDate({
            isinitVal:true,
            isClear:false,
            //festival:true,
            //ishmsVal:false,
            // minDate: $.nowDate({DD:0}),
            //maxDate: $.nowDate({DD:0}),
            hmsSetVal:{hh:09,mm:00,ss:00},
            format:"hh:mm:ss",
            zIndex:3000,
        })
        $("#date06").jeDate({
            isinitVal:true,
            festival:true,
            ishmsVal:false,
            isClear:false,
            /*设置默认的时间*/
            hmsSetVal:{hh:15,mm:00,ss:00},
            //minDate: $.nowDate({DD:0}),
            // maxDate: $.nowDate({DD:0}),
            format:"hh:mm:ss",
            zIndex:3000,
            //选择时间后的回调
            choosefun: function(elem,datas){
                //arrTimer();
            },
            //点击确定后的回调
            okfun:function(val) {
                //arrTimer();
            }
        })
        $("#date07").jeDate({
            isinitVal:true,
            isClear:false,
            //festival:true,
            //ishmsVal:false,
            // minDate: $.nowDate({DD:0}),
            //maxDate: $.nowDate({DD:0}),
            hmsSetVal:{hh:09,mm:00,ss:00},
            format:"hh:mm:ss",
            zIndex:3000,
        })
        $("#date08").jeDate({
            isinitVal:true,
            isClear:false,
            //festival:true,
            //ishmsVal:false,
            //minDate: $.nowDate({DD:0}),
            // maxDate: $.nowDate({DD:0}),
            hmsSetVal:{hh:15,mm:00,ss:00},
            format:"hh:mm:ss",
            zIndex:3000,
        });
    }

    $(function(){
        setTime()
    })



