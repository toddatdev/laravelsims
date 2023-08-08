
var itemNumber = 1;
$(".alert-danger").addClass('hide');
$(".alert-success").addClass('hide');

$('body').on('click', '.addSubLevel', function (event) {
            var addLink = $(this).parent().closest('div').find('a.addLevel');
            var newCurrentItemLevel = parseInt(addLink.attr('data-current-level')) + 1;
        
            itemNumber++;
            var parentId = $(this).parent().closest('div').attr('item-id');
            
            var element = '<div id="rootItem" class = "answersFieldHolder item" item-id = "'+itemNumber+'" item-name ="" parent-id = "'+parentId+'" checkValue="0" >';
            element += '<a data-current-level = "'+newCurrentItemLevel+'" class = "addLevel pull-left" href = "#" ><i class="fa fa-plus-circle" aria-hidden="true" title="Add Answer"></i></a >';
            element +='<a class="removeLevel pull-left" href = "#"> <i class="fa fa-minus-circle" aria-hidden="true" title="Remove Answer"></i></a>';
            element +='<a class="addSubLevel sub-Level pull-left" href="#"><i class="fa fa-level-down" aria-hidden="true" title="Add Child Answer"></i></a>';
            element +='<input class="pull-left qAnsField" type = "text" value = "" name = "" / >';
            element +='<input class="commentNeeded pull-left" type = "checkbox" name = "has_comment" title="Comment Needed"/ ><label for="has_comment" class="chkbox">Requires text field</label>';
            element +='<div class="clearfix clear"> </div>';
            element +='</div>';

            var elementLocation = $(this).parent().closest('div');
            element = $(element).css('margin-left', (newCurrentItemLevel * 10) + 'px');
            elementLocation.append(element);
            return elementLocation;
        
});

$('body').on('click', '.addLevel', function(e) {
    e.preventDefault();
        var newCurrentItemLevel = parseInt($(this).attr('data-current-level'));
        itemNumber++;
        var parentId = $(this).parent().attr('parent-id');
        console.log(parentId)

        var element = '<div class = "answersFieldHolder item" item-id = "'+itemNumber+'" item-name ="" parent-id = "'+parentId+'" checkValue="0" >';
        element += '<a data-current-level = "'+newCurrentItemLevel+'" class = "addLevel pull-left" href = "#" ><i class="fa fa-plus-circle" aria-hidden="true" title="Add Answer"></i></a >';
        element +='<a class="removeLevel pull-left" href = "#"> <i class="fa fa-minus-circle" aria-hidden="true" title="Remove Answer"></i></a>';
        element +='<a class="addSubLevel sub-Level pull-left" href="#"><i class="fa fa-level-down" aria-hidden="true" title="Add Child Answer"></i></a>';
        element +='<input class="pull-left qAnsField" type = "text" value = "" name = "" / >';
        element +='<input class="commentNeeded pull-left" type = "checkbox" name = "has_comment" title="Comment Needed"/ ><label for="has_comment" class="chkbox">Requires text field</label>';
        element +='<div class="clearfix clear"> </div>';
        element +='</div>';

        var elementLocation = $(this).parent();
        element = $(element).css('margin-left', (newCurrentItemLevel * 10) + 'px');
        elementLocation.after(element);
        return elementLocation;
        
});



$('body').on('click', '#submit-user-proifle-questions', function(e) {
    $(".alert-danger,.alert-success").addClass('hide');
    e.preventDefault(); // prevent default submit behaviour 

    var answersData = [];
    var thisForm = $('#userProfileForm');
    var questionData = $('#userProfileForm').find('input[name=question]').val();
    var question_id = $('#userProfileForm').find('input[name=question_id]').val();

    
    var answersFieldCount = thisForm.find('.item').length;
        
    thisForm.find('.item').each(function (elem, field) {
        if ($(field).attr('item-name')) {
            answersData.push({
                id: parseInt($(field).attr('item-id')),
                name: $(field).attr('item-name'),
                parent_id: parseInt($(field).attr('parent-id')),
                comment_needed: $(field).attr('checkValue'),
            })
        }
    })

    console.log(answersData);

   // get form action url
   var formActionURL = thisForm.attr("action");
   $("#submit-user-proifle-questions").val('please wait...');

    $.ajax({
        url: formActionURL,
         type: "POST",
         data: { 'answers_field_count': answersFieldCount,
             //'check_filed_count': checkFieldCount,
             'question': questionData, 
             'question_id': question_id,
             'answers': answersData},
    }).done(function(data) {
        $(".alert-success").append("ul").html(data.message);
        $(".alert-success").removeClass('hide');
        $("#submit-user-proifle-questions").hide();
        window.location = data.url;
    }).fail(function(jqXHR, textStatus, errorThrown) {
            printErrorMsg(jqXHR.responseJSON);
    }).always(function() {
        $("#submit-user-proifle-questions").val('Create');
    });
});
        

$('body').on('click', 'a.removeLevel', function (event) {
    
    event.preventDefault();
    
    var parentId = $(this).parent().closest('div').attr('parent-id');
    var parentElement = $('div.item').find('[parent-id=' + parentId + ']');
    if (parentElement.length == 1) {
        $('div').find('[item-id=' + parentId + ']').find('input[type=checkbox]').prop('checked', false);
    }

    $(this).parent().closest('div').remove();
});

       
function printErrorMsg (msg) {
    $(".alert-danger").append("ul").html('');
    $(".alert-danger").removeClass('hide');
    $.each(msg.errors, function(key, value) {
       $(".alert-danger").append('<li>' + value.join(',') + '</li>');
    });
}

$('body').on('change', 'input.qAnsField', function () {
    $(this).parent().closest('div').attr('item-name', $(this).val());
});

$("body").on("click",".commentNeeded", function(){
    var elements = $(this).closest('.answersFieldHolder').find('.answersFieldHolder');
    if($(this).prop('checked')){
        $(this).closest('.answersFieldHolder').find('.sub-Level').removeClass('addSubLevel').addClass('disable');
        $(this).closest('.answersFieldHolder').attr('checkValue','1');
        elements.remove();
    } else {
        $(this).closest('.answersFieldHolder').find('.sub-Level').removeClass('disable').addClass('addSubLevel');
        $(this).closest('.answersFieldHolder').attr('checkValue','0');
    }
});