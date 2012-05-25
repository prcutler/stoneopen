function changeFontSize(inc)
{
  var p = document.getElementsByTagName('div');
  for(n=0; n<p.length; n++) {
    if(p[n].style.fontSize) {
       var size = parseInt(p[n].style.fontSize.replace("px", ""));
    } else {
       var size = 12;
    }
    if(inc==0){
      p[n].style.fontSize ='12px';
    }
    else {
    p[n].style.fontSize = size+inc + 'px';
   }
   }
}
