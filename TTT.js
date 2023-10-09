
let userinput,comp, Rock='Rock', Paper='Paper', Scissor='Scissor', result, score=JSON.parse(sessionStorage.getItem('score')) || { win:0,losses:0,ties:0},Reset='Reset';
function hello(userinput){
  comp = Math.random(); 
  if(comp<=1/3)
    comp='Rock';
    else if(comp>=2/3)
      comp='Paper';
    else
    comp='Scissor';

 if(userinput==comp)
  {
    result='TIE for both';
    score.ties++
  }

  else if((userinput==Rock && comp==Paper) || (userinput==Paper && comp==Scissor) || (userinput==Scissor && comp==Rock))
      {
        result='Lost for you';
        score.losses++;
      }

  else if(userinput==Reset)
  {
    score.losses=0;
    score.win=0;
    score.ties=0;
    comp='to agree';
    result='score reset';
    sessionStorage.removeItem(score);
    
  }
  
  else 
      {
        result='WIN for you';
        score.win++;
      }
  sessionStorage.setItem('score',JSON.stringify(score));
  update();

  function update(){
  document.querySelector('.js_score').innerHTML=
  `You Chose ${userinput}.<br>Computer chose ${comp}!<br> It's a ${result}.<br>Score: Wins:${score.win} Losses:${score.losses} Ties:${score.ties}`;
   
    }

}
