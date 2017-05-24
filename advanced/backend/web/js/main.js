
/**
 * Created by sashko on 3/21/2017.
 */
$(function() {
    //get the click of the create button
    $('#modalButton').click(function () {   //при натискане на бутона с ID modalButton
          $('#modal').modal('show')
            .find('#modalContent')
            .load($(this).attr('value'));
    })
});