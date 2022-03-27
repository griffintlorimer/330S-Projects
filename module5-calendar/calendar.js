let num = 0;
// let clicked = null;

const calendar = document.getElementById('calendar');
const weekdays = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];

function calendars_(){
    const dt = new Date();
    
    if (num !== 0) {
      dt.setMonth(new Date().getMonth() + num);
    }

    // const day = dt.getDate();
    const month = dt.getMonth();
    const year = dt.getFullYear();
   
    const firstDay = new Date(year, month, 1);
    const daysMonth = new Date(year, month+1,0).getDate();

    const dateString = firstDay.toLocaleDateString('en-us', {
    weekday: 'long',
    year: 'numeric',
    month: 'numeric',
    day: 'numeric',
  });
  
    const Days = weekdays.indexOf(dateString.split(', ')[0]);

    document.getElementById('month').innerText = 
  `${dt.toLocaleDateString('en-us', { month: 'long' })} ${year}`;

    calendar.innerHTML ='';
  
    for(let i = 1; i <= Days + daysMonth; i++){
    const dayBox = document.createElement('div');
    dayBox.classList.add('day');
    let i_str = String(i);
    dayBox.setAttribute("id", i_str);

    if(i>Days){
      dayBox.innerText = i - Days;

      dayBox.addEventListener('click',() => console.log('click'));

    } 
    // else{
    //    daySquare.classList.add('padding')
    // }
    
   calendar.appendChild(dayBox);
  
  }
}

function Buttons() {
  document.getElementById('next').addEventListener('click', () => {
    num++;
    calendars_();
  });

  document.getElementById('back').addEventListener('click', () => {
    num--;
    calendars_();
  });
}

Buttons();
calendars_();