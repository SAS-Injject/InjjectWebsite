window.onload = async (event) => {
  let legals_container = document.querySelector('article[data-legals=true]');
  if (!!legals_container && !!legals_container.dataset.notices) {
    let json_parsed = {}
    try {
      const response = await fetch(`https://api.injject.com/api/${legals_container.dataset.notices}`);
      if(!response.ok) {
        throw new Error(`Response status: ${response.status}`);
      }

      let json =  await response.json();
      json_parsed = JSON.parse(json);
    } catch (error) {
      console.error(error.message);
    }
    console.log(json_parsed);
    legals_container.innerHTML = json_parsed.data.html;
  } 
}