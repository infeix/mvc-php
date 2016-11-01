
var current_page;
var page_count;

function execute_pagination()
{
    current_page = 1;
    $('form.paginate').each(function (){
        var div = $(this);
        var pagesize = div.attr('pagesize');
        page_count = 0;
        var conent_index = 0;
        var currentdiv =  null;
        div.children('div.form-group').each(function (){
            if(conent_index >= pagesize)
            {
                conent_index = 0;
            }
            if(conent_index === 0)
            {
                currentdiv = $( '<div class="page page-' + (page_count+1) + '"></div>' );
                page_count += 1;
                div.prepend(currentdiv);                
                currentdiv.hide();
            }
            currentdiv.append($(this));
            $(this).show();
            conent_index += 1;
        });
        $('.count-pages').each(function (){
            $(this).html(page_count);
        });
    });
    if(page_count > 0){
       show_current_page(); 
    }
}

function show_current_page(page)
{
    if (typeof(page)!=='undefined') current_page = page;
    $('.paginate').each(function (){
        var div = $(this);
        div.children('div').each(function (){
            $(this).hide();
        });
    });
    $('.page-' + current_page).each(function (){
        $(this).show();
    });
    $('.current-page').each(function (){
        $(this).html(current_page);
    });
    if(current_page === page_count) {
        $('.btn-primary').removeClass("disabled");
    }else{
        $('.btn-primary').addClass("disabled");
    }
}

function prev_page(validate_callback)
{
    if (typeof(validate_callback)!=='undefined') validate_callback = (function(){return true;});
    next_page(validate_callback, -1);
    return false;
}
function next_page(validate_callback, offset)
{
    if(typeof(offset) ==='undefined') offset = 1;
    var valid = true;
    if (typeof(validate_callback)!=='undefined')
        valid = (current_page + offset >= 1) && (current_page + offset <= page_count) && validate_callback(current_page);
    if(valid && current_page + offset <= page_count)
    {
        show_current_page(current_page + offset);
    }
    return false;
}

$( document ).ready(execute_pagination());