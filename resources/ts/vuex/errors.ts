export interface QuizzAppError {
    type: ErrorType;
    message: string;
}

export enum ErrorType {
    SERVER_ERROR
}

export const getError = (type: ErrorType, message: string|null = null): QuizzAppError => {
    return {
        type: type,
        message: message ?? String(type)
    };
};
