function render(data){
    var html = "<div class='commentBox'><div class= 'leftPanelImg'><img src='https://placeholdit.imgix.net/~text?w=100&h=100' /></div><div class='rightPanel'><span>"+data.name+"</span><div class='date'>"+data.date+"</div><p>"+data.body+"</p></div><div class='clear'></div></div>";
    $('#container').append(html);
}
  
$(document).on("ready",function(){
    var comment = [
        {"name": "Nima", "date": "10 nov", "body": "this is a comment"}
    ];
   
    for(var i=0; i<comment.length; i++){
        render(comment[i]);
    }
  
    $('#addComment').on("click", function(){
        var addObj = {
            "name": $('#name').val(),
            "date": $('#date').val(),
            "body": $('#bodyText').val()
        };
        comment.push(addObj);
        render(addObj);
        $('#name').val('');
        $('#date').val('dd/mm/yyyy');
        $('#bodyText').val('');
    });
  
});