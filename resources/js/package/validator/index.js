import Vue from 'vue';
import VeeValidate from 'vee-validate';
import {Validator} from 'vee-validate';
import dictionary from './dictionary';

const config = {
 errorBagName: 'errors', // change if property conflicts.
  fieldsBagName: 'fields',
  delay: 0,
  locale: 'jp',
  dictionary,
  strict: true,
  classes: false,
  classNames: {
    touched: 'touched', // the control has been blurred
    untouched: 'untouched', // the control hasn't been blurred
    valid: 'valid', // model is valid
    invalid: 'invalid', // model is invalid
    pristine: 'pristine', // control has not been interacted with
    dirty: 'dirty' // control has been interacted with
  },
  events: 'blur',
  inject: true,
  validity: true,
  aria: true

}

Vue.use(VeeValidate,config);

import rules from './rules';

for(let name in rules){
    var rule = rules[name];
    Validator.extend(name,rule)
}

export default VeeValidate;

/* Author: AjDesamparado
 * Company: Freemight
 * Editor: Vim
 * Settings: tabstop=4 shiftwidth=4 expandtab=1 softtabstop=0 */
