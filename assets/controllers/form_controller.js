import {Controller} from '@hotwired/stimulus';

/*
 * This controller manage the form and show elements
 * div is composed by an show panel and a form panel (hidden)
 * this trigger hide show template and show form on button click
 */
export default class extends Controller {

    static targets = ['button', 'show', 'form']

    connect() {
        console.log('trigger works !')
    }

    toggle() {
        // console.log(this.showTarget !== undefined)
        this.showTarget.classList.toggle("opacity-0")
        // this.showTarget.classList.toggle("hidden")
        this.showTarget.classList.toggle("max-w-0")
        // this.showTarget.classList.toggle("-ml-80")
        this.formTarget.classList.toggle("opacity-0")
        // this.formTarget.classList.toggle("hidden")
        if (this.buttonTarget.innerText === 'Update') this.buttonTarget.innerText = "Show"
        else this.buttonTarget.innerText = 'Update'
        // if (this.mobileTarget !== undefined){
        //     this.navTarget.classList.toggle("-mt-[50vh]")
        // }
    }
}
