import { HttpClient, HttpHeaders, HttpParams } from '@angular/common/http';
import { Injectable } from '@angular/core';
import { filter, map, of, take } from 'rxjs';
import { environment } from 'src/environments/environment';
import { Member } from '../_models/member';
import { PaginatedResult } from '../_models/pagination';
import { User } from '../_models/user';
import { UserParams } from '../_models/userParams';
import { AccountService } from './account.service';
import { getPaginatedResult, getPaginationHeaders } from './paginationHelper';

@Injectable({
  providedIn: 'root'
})
export class MembersService {


  baseUrl = environment.apiUrl;
  members: Member[] = [];
  memberCache = new Map();
  userParams: UserParams | undefined;
  user: User | undefined;


  constructor(private http: HttpClient,private accountService: AccountService) {
    this.createUserParams();
   }

   public createUserParams(){
    this.accountService.currentUser$.pipe(take(1)).subscribe({
      next: user => {
        if (user) {
          this.userParams = new UserParams(user);
          this.user = user;
        }
      }
    })
   }

   getUserParams() {
    return this.userParams;
   }

   setUserParams(params: UserParams){
    this.userParams = params;
   }

   resetUserParams(){
    if(this.user) {
      this.userParams = new UserParams(this.user);
      return this.userParams;
    }
    return ;
   }

  getMembers(userParams: UserParams) {
    const resposne = this.memberCache.get(Object.values(userParams).join('-'));
    
    let params = getPaginationHeaders(userParams.pageNumber, userParams.pageSize);

    params = params.append('minAge',userParams.minAge);
    params = params.append('maxAge',userParams.maxAge);
    if(userParams.gender && userParams.gender!=='')
    params = params.append('gender',userParams.gender);
    params = params.append('orderBy', userParams.orderBy);
    params = params.append('withoutLikes', userParams.withoutLikes);

    return getPaginatedResult<Member[]>(this.baseUrl + 'users', params,this.http).pipe(
      map(resposne => {
        this.memberCache.set(Object.values(userParams).join('-'), resposne);
        return resposne;
      })
    );
  }
  getBestMembers() {  
    let params = getPaginationHeaders(1, 3);
    params = params.append('orderBy', 'points');
    params = params.append('withoutLikes', false);
    return getPaginatedResult<Member[]>(this.baseUrl + 'users', params,this.http).pipe(
      map(resposne => {
        return resposne;
      })
    );
  }


 

  getMember(username: string) {
    return this.http.get<Member>(this.baseUrl + 'users/' + username);
  }

  updateMember(member: Member) {
    return this.http.put(this.baseUrl + 'users', member).pipe(
      map(() => {
        const index = this.members.indexOf(member);
        this.members[index] = { ...this.members[index], ...member }
      })
    );
  }

  setMainPhoto(photoId: number) {
    return this.http.patch(this.baseUrl + 'users/photos/' + photoId, {})
  }

  deletePhoto(photoId: number) {
    return this.http.delete(this.baseUrl + 'users/photos/' + photoId, {});
  }

  addLike(username: string){
    return this.http.post<any>(this.baseUrl + 'likes/' + username, {});
  }
  unLike(username: string){
    return this.http.delete(this.baseUrl + 'likes/' + username, {});
  }
  dislike(username: string){
    return this.http.post(this.baseUrl + 'dislikes/' + username, {});
  }
  resetDislikes(){
    return this.http.delete(this.baseUrl + 'dislikes/reset', {});
  }


  getLikes(predicate: string,pageNumber: number, pageSize: number){

    let params = getPaginationHeaders(pageNumber, pageSize);

    params = params.append('predicate',predicate);

    return getPaginatedResult<Member[]>(this.baseUrl + 'likes', params,this.http);
  }

  reportUser(username: string,values : any){
    return this.http.post<string>(this.baseUrl + 'users/'+ username + '/report',values);
  }
}
