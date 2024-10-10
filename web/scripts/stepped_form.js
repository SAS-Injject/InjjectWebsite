console.log('prout');

  let DROP_CONTAINERS = document.querySelectorAll(".drop-container");

  DROP_CONTAINERS.forEach((DROP_CONTAINER) => {

    let FILE_INPUT = document.querySelector(`input[data-drop]#${DROP_CONTAINER.getAttribute('for')}`);

    
    DROP_CONTAINER.addEventListener("dragover", (e) => {
      e.preventDefault()
    }, false);

    DROP_CONTAINER.addEventListener("dragenter", () => {
      DROP_CONTAINER.classList.add("drag-active")
    });

    DROP_CONTAINER.addEventListener("dragleave", () => {
      DROP_CONTAINER.classList.remove("drag-active")
    });

    DROP_CONTAINER.addEventListener("drop", (e) => {
      e.preventDefault()
      DROP_CONTAINER.classList.remove("drag-active")
      FILE_INPUT.files = e.dataTransfer.files
    });
  })

  let forms = document.querySelectorAll('form[data-i-form=steps]');

  forms.forEach(form => {
    let fieldsets = form.querySelectorAll('fieldset[data-i-step]')

    let current_step = 1
    let max_step = fieldsets.length

    let move_buttons = form.querySelectorAll('button[data-i-destination]')

    fieldsets.forEach(fieldset => {
      fieldset.dataset.visible = "false"
    })

    form.querySelector('fieldset[data-i-step="'+current_step+'"]').dataset.visible = "true"

    move_buttons.forEach(button => {
      button.onclick = (event) => {
        event.preventDefault()


        let next_step = button.dataset.iDestination
        let next_fieldset = form.querySelector('fieldset[data-i-step="'+next_step+'"]')
        let current_fieldset = form.querySelector('fieldset[data-i-step="'+current_step+'"]')

        let inputs = current_fieldset.querySelectorAll('[data-i-validity]');
        console.log(current_fieldset)
        let isValid = true
        inputs.forEach(input => {
          console.log(input, input.checkValidity())
          if(next_step > current_step && !input.checkValidity()){
            isValid = false
            input.reportValidity()
          }
        })
        console.log(isValid, inputs)

        if(isValid) {
          hide_fieldsets(fieldsets)
          current_step = next_step;
          next_fieldset.dataset.visible = "true"
        } 
      }
    })

  })



function hide_fieldsets(fieldsets) {
  fieldsets.forEach(fieldset => {
    fieldset.dataset.visible = "false"
  })
}