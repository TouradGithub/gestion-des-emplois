
function mapInfoSource(val) {
    $("#objectCnt").show();
    getTheContent(  'maps/infos/getObject/'+val+"/"+$("#niveau_geo").val(), '#objectCnt');
}
