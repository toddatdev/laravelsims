$('body').on('change', 'select.user_profile_answer_id', (function (e) {

    var user_profile_answer_id = $(this).val();
    var comment_needed = $('option:selected', this).attr('comment_needed');
    var comment_question_id_needed = $('option:selected', this).attr('comment_needed');
    console.log(comment_needed);
    var user_profile_answer_field_name = $(this).attr('name');
    console.log(user_profile_answer_field_name);
    var parentDiv = $(this).parent().closest('div.ansSelectBox');
    
    parentDiv.nextAll('.ansSelectBox').remove();
    
    if(comment_needed == 1) {
       var filedName = user_profile_answer_field_name.replace("answer","comments");
      parentDiv.after('<div class="ansSelectBox"><input style="margin-top:10px" class="user_profile_answer_id form-control col-md-5" name="'+filedName+'" type="text" value="" /></div>'); 
    } 
    else 
    {
            $.get(baseUrl+"/question/answer/" + user_profile_answer_id, function (data, status) {

            if(data.length > 0) {
            var optionText = '<option value="">Select answer</option>';
            $.each(data, function (id, data) {
                optionText += '<option comment_needed="'+ data.comment_needed+'" value="' + data.user_profile_answer_id + '">' + data.answer_text + '</option>';
            });
             parentDiv.after('<div class="ansSelectBox"><select name="'+user_profile_answer_field_name+'" style="margin-top:10px" class="user_profile_answer_id form-control col-sm-5">' + optionText + '</select></div>');  

            } else {}
        });
    }

}));

