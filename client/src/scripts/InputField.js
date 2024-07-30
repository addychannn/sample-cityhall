/**
 * @author Jhyden Jhoe Marx M. Ellana
 * @version 1.0
 */
import jvalidate from "./jvalidate.js";
import Helpers from "./Helpers.js";

if (!String.prototype.format) {
  String.prototype.format = function () {
    var args = arguments;
    return this.replace(/{(\d+)}/g, function (match, number) {
      return typeof args[number] != "undefined" ? args[number] : match;
    });
  };
}

let inputF = class InputField {
  constructor(initialValue = null) {
    this.setValue(initialValue);
    this.error = false;
    this.errorMessage = "";
    this.name = "This field";
    this.setId(Helpers.uniqid());
    this.type = "text";
    this.rules = null;
  }

  /**
   * Set the name of the field
   *
   * @param {String} name | Name of the field
   * @returns | Returns the Input Field instance
   */
  setName(name) {
    this.name = name;
    return this;
  }

  /**
   * Sets the id of the field
   *
   * @param {String} id
   * @returns | Returns the Input Field instance
   */
  setId(id) {
    this.id = id;
    return this;
  }

  /**
   * Set the value of the field
   *
   * @param {Any} value
   * @param {Boolean} deepcopy | Deep copy the passed value instead of setting a reference
   * @returns | Returns the Input Field instance
   */
  setValue(value, deepcopy = true) {
    this.value = deepcopy ? JSON.parse(JSON.stringify(value)) : value;
    return this;
  }

  /**
   *
   *
   * @param {String | Array} rules
   * @returns
   */
  setRules(rules) {
    this.rules = rules;
    return this;
  }

  setType(type) {
    this.type = type;
    return this;
  }

  reset(val = null) {
    this.value = val;
    this.resetError();
    return this;
  }

  resetError() {
    this.error = false;
    this.errorMessage = "";
    return this;
  }

  setError(errorMessage) {
    this.error = true;
    this.errorMessage = errorMessage;
    return this;
  }

  getName() {
    return this.name;
  }

  validate(rules = null) {
    let validators = rules ?? this.rules;
    if (!validators) return true;
    this.resetError();
    let result = true;
    let jvalidator = new jvalidate();
    if (typeof validators === "object") {
      let rules = [];
      if (typeof validators.rules == "string") {
        rules = validators.rules.split("|");
      } else {
        rules = validators.rules;
      }
      for (let i = 0; i < rules.length; i++) {
        let rule = rules[i];
        let index = rule.indexOf(":");
        let ruleName = index > -1 ? rule.substr(0, index) : rule;
        let ruleValue = rule.substr(index + 1);
        let validatorErrorMessage =
          validators.messages?.[ruleName] ??
          jvalidator.Validators[ruleName].errorMessage;
        let validatorMethod = jvalidator.Validators[ruleName].method;
        if (!validatorMethod(this.value, ruleValue)) {
          result = false;
          this.setError(
            validatorErrorMessage.format(this.getName(), ruleValue)
          );
          break;
        }
      }
    } else {
      let rules = validators.split("|");
      for (let i = 0; i < rules.length; i++) {
        let rule = rules[i];
        let index = rule.indexOf(":");
        let ruleName = index > -1 ? rule.substr(0, index) : rule;
        let ruleParams = rule.substr(index + 1);
        let ruleMethod = jvalidator.Validators[ruleName].method;
        let ruleErrorMessage = jvalidator.Validators[ruleName].errorMessage;
        if (!ruleMethod(this.value, ruleParams)) {
          result = false;
          this.setError(ruleErrorMessage.format(this.getName(), ruleParams));
          break;
        }
      }
    }
    return result;
  }
};

export default inputF;
