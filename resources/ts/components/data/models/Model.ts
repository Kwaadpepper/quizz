export default abstract class Model {
    constructor(model: LaravelModel) {
        Object.keys(this).forEach((key) => ((this[key as never] as never) = model[key] as never));
    }
}
