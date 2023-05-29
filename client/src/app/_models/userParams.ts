import { User } from "./user";

export class UserParams {
    gender: string= '';
    minAge = 18;
    maxAge = 99;
    pageNumber = 1;
    pageSize = 7;
    orderBy = 'created_at';

    constructor(user?: User){
        console.log(user);
        
        if(user)
        this.gender = (user.gender === 'female') ? 'male' : 'female';
    }

    
}