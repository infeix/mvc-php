
$('#buttonLogin').click(function(){
    var hash = md5($('#inputPassword').val());
    
    $('#hiddenInputPassword').val(hash);
    $('#inputPassword').val('');
    $('#formLogin').submit(); 
});