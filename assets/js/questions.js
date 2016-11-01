
function send_question_form()
{
    var valid = validate_form();
    var form_element = $('form#questions-form');  
    var queryString = form_element.serializeArray();
    if(valid)
    {
        console.log(JSON.stringify(queryString));
        var new_form = $('<form>', {
            "id": "final-form",
            "html": '<input type="hidden" name="Results" value=\'' + JSON.stringify(queryString) + '\' />',
            "action": '/result/create',
            "method": 'POST'
        });
        new_form.appendTo(document.body);
        new_form.submit();
    }
    return false;
}

function validate_form(page)
{
    if (typeof(page)==='undefined')
        select_string = 'form#questions-form';
    else
        select_string = 'div.page-' + page;
    
    var form_element = $('form#questions-form');
    var queryString = form_element.serializeArray();
    var valid = true;
    $(select_string + ' input.required').each(function(){
        var name = $(this).attr('name');
        var isset = false;
        for(var element in queryString)
        {
            if(queryString[element].name === name)
            {
                isset = true;
                break;
            }
        }
        if(!isset)
        {
            $('.form-group.' + name).addClass('warning');
            valid = false;
        }
        else
        {
            $('.form-group.' + name).removeClass('warning');
        }
    });
    return valid;
}

$(function  () {
    var group = $("ol.sortable-answers").sortable({
        group: 'sortable-answers',
        delay: 200,
        onDrop: function ($item, container, _super) {
            var data = group.sortable("serialize").get();
            var rating = data[0].length - 1;
            for(var element in data[0])
            {              
                $('#' + data[0][element].id).attr('name', data[0][element].name + '-' + rating);
                rating -= 1;
            }
            _super($item, container);
        }
    });
});
