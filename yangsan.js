    // 정보 조회

    function search(){

        let dong = $('#dong').val();
        let food = $('#food').val();

        let senddata = new Object();

        senddata.dong = dong;
        senddata.food = food;

        render('yangsan_result', senddata);  

        };


