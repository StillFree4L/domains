window.onkeydown = function(e) {
    if (e.key === 'Tab') {
      if($('td.x-grid-item-focused')[0] && $('td.'+$('td.x-grid-item-focused')[0].classList[2])[$('td.'+$('td.x-grid-item-focused')[0].classList[2]).index($('td.x-grid-item-focused')[0])+1]){
        $('td.'+$('td.x-grid-item-focused')[0].classList[2])[$('td.'+$('td.x-grid-item-focused')[0].classList[2]).index($('td.x-grid-item-focused')[0])+1].click();
      }else if($('td.x-grid-item-focused')[0] && $('td.'+$('td.x-grid-item-focused')[0].classList[2])[0]){
        $('td.'+$('td.x-grid-item-focused')[0].classList[2])[0].click();
      }
      return false;
    }
    if (e.key === 'Enter') {
      if($('td.x-grid-item-focused')[0] && $('td.x-grid-cell.x-grid-td')[$('td.x-grid-cell.x-grid-td').index($('td.x-grid-item-focused')[0])+1]){
        $('td.x-grid-cell.x-grid-td')[$('td.x-grid-cell.x-grid-td').index($('td.x-grid-item-focused')[0])+1].click();
      }else if($('td.x-grid-item-focused')[0] && $('td.x-grid-cell.x-grid-td')[0]){
        $('td.x-grid-cell.x-grid-td')[0].click();
      }
      return false;
    }
}

  function inputKey(e,event) {

    if(event.key === 'Enter'){
      let all = $("input.inputValue");
      if(all.index(e)+1 !== all.length){
        all[all.index(e)+1].focus();
        all[all.index(e)+1].click();
      }else if(all[all.index($("input.inputValue[idd="+e.getAttribute('idd')+"]")[0])]){
        all[all.index($("input.inputValue[idd="+e.getAttribute('idd')+"]")[0])].focus();
        all[all.index($("input.inputValue[idd="+e.getAttribute('idd')+"]")[0])].click();
      }
      return false;
    }

    else if(event.key === 'ArrowUp'){
      let inp = $("input.inputValue[id="+e.id+"]");
      if(inp[inp.index(e)-1]){
        inp[inp.index(e)-1].focus();
        inp[inp.index(e)-1].click();
      }else if(inp[inp.length-1]){
        inp[inp.length-1].focus();
        inp[inp.length-1].click();
      }
      return false;
    }

    else if(event.key === 'ArrowDown' || event.key === 'Tab'){
      let inp = $("input.inputValue[id="+e.id+"]");
      if(inp[inp.index(e)+1]){
        inp[inp.index(e)+1].focus();
        inp[inp.index(e)+1].click();
      }else if(inp[0]){
        inp[0].focus();
        inp[0].click();
      }
      return false;
    }

    else if(event.key === 'ArrowLeft'){
      let all = $("input.inputValue");
      if(all[all.index(e)-1]){
        all[all.index(e)-1].focus();
        all[all.index(e)-1].click();
      }else if(all[all.index($("input.inputValue[idd="+e.getAttribute('idd')+"]")[$("input.inputValue[idd="+e.getAttribute('idd')+"]").length-1])]){
        all[all.index($("input.inputValue[idd="+e.getAttribute('idd')+"]")[$("input.inputValue[idd="+e.getAttribute('idd')+"]").length-1])].focus();
        all[all.index($("input.inputValue[idd="+e.getAttribute('idd')+"]")[$("input.inputValue[idd="+e.getAttribute('idd')+"]").length-1])].click();
      }
      return false;
    }

    else if(event.key === 'ArrowRight'){
      let all = $("input.inputValue");
      if(all[all.index(e)+1]){
        all[all.index(e)+1].focus();
        all[all.index(e)+1].click();
      }else if(all[all.index($("input.inputValue[idd="+e.getAttribute('idd')+"]")[0])]){
        all[all.index($("input.inputValue[idd="+e.getAttribute('idd')+"]")[0])].focus();
        all[all.index($("input.inputValue[idd="+e.getAttribute('idd')+"]")[0])].click();
      }
      return false;
    }

  }
