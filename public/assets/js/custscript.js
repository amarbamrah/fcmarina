$(document).ready(function(){
    $('#stypebox').on('change',function(){
        $stype=$(this).val();
        if($stype=="5s"){
            $('.7sprices').find('input').attr('disabled','disabled');
            $('.7sprices').find('input').val('');


            $('.5sprices').find('input').removeAttr('disabled');

            $('.7sprices').find('input').removeAttr('required');
            $('.5sprices').find('input').attr('required','required');


        }else if($stype=="7s"){
            $('.5sprices').find('input').attr('disabled','disabled');
            $('.7sprices').find('input').removeAttr('disabled');
            $('.5sprices').find('input').val('');


            $('.5sprices').find('input').removeAttr('required');
            $('.7sprices').find('input').attr('required','required');


        }else{
            $('.7sprices').find('input').removeAttr('disabled');
            $('.5sprices').find('input').removeAttr('disabled');

            $('.5sprices').find('input').attr('required','required');
            $('.7sprices').find('input').attr('required','required');



        }


       
    })


    $('#applicable').on('change',function(){
        if($(this).val()=='all'){
            $('#userbox').css('display','none');

        }else{
            $('#userbox').css('display','block');

        }
    });
});