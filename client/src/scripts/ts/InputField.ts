
interface InputFieldInterface {
    data: unknown;
    error: Boolean;
    errorMessage: String;
    name: String;
    setName(name:String | null): InputFieldInterface;
    setValue(value: unknown | null): InputFieldInterface;
    setError(message: String | null): InputFieldInterface;
    resetError(): InputFieldInterface;
    reset(value: unknown | null): InputFieldInterface;
}

class InputField implements InputFieldInterface {
    name: String;
    error: Boolean;
    errorMessage: String;
    data: unknown;

    constructor(Initial: unknown, name: String = "This field") {
        this.resetError();
        this.name = name;
        this.data = Initial;
       
    }

    setValue (value: unknown): InputFieldInterface {
        this.data = value;
        this.error = false;
        this.errorMessage = null;
        return this;
    }

    setName (name: String): InputFieldInterface {
        this.name = name;
        return this;
    }

    setError (message: String): InputFieldInterface {
        this.errorMessage = message;
        this.error = true;
        return this;
    }

    resetError (): InputFieldInterface{
        this.error = false;
        this.errorMessage = null;
        return this;
    }

    reset (): InputFieldInterface {
        return this;
    }
}


export default InputField;