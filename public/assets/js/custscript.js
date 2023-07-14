$(document).ready(function(){
    $('#stypebox').on('change',function(){
        $stype=$(this).val();
        if($stype=="5s"){
            $('.7sprices').find('input').attr('disabled','disabled');
            $('.7sprices').find('input').val('');

            $('.5sprices').find('input').removeAttr('disabled');

        }else if($stype=="7s"){
            $('.5sprices').find('input').attr('disabled','disabled');
            $('.7sprices').find('input').removeAttr('disabled');
            $('.5sprices').find('input').val('');


        }else{
            $('.7sprices').find('input').removeAttr('disabled');
            $('.5sprices').find('input').removeAttr('disabled');


        }


       
    })
});