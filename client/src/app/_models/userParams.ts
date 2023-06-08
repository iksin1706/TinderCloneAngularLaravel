import { User } from "./user";

export class UserParams {
    gender: string= '';
    minAge = 18;
    maxAge = 99;
    pageNumber = 1;
    pageSize = 5;
    orderBy = 'created_at';
    withoutLikes = false;

    constructor(user?: User){
        if(user)
        this.gender = (user.gender === 'female') ? 'male' : 'female';
    }

    
}