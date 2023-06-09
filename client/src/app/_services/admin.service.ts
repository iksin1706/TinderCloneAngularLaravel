import { HttpClient } from '@angular/common/http';
import { Injectable } from '@angular/core';
import { map } from 'rxjs';
import { environment } from 'src/environments/environment';
import { Report } from '../_models/report';
import { User } from '../_models/user';

@Injectable({
  providedIn: 'root'
})
export class AdminService {

  baseUrl = environment.apiUrl;

  constructor(private http: HttpClient) { }

  getUsersWithRoles(){
    return this.http.get<User[]>(this.baseUrl + 'admin/users')
  }

  updateUserRoles(username: string, role: string) {
    return this.http.patch<string>(this.baseUrl + 'admin/users/' + username + '?role=' + role, {});
  }
  banUser(username: string,values : any){
    return this.http.post<string>(this.baseUrl + 'users/'+ username + '/ban',values);
  }
  unbanUser(username: string){
    return this.http.post<string>(this.baseUrl + 'users/'+ username + '/unban',{});
  }

  getReports(){
    return this.http.get<Report []>(this.baseUrl + 'admin/reports')
  }
  getRoles(){
    return this.http.get<any []>(this.baseUrl + 'admin/roles').pipe(
      map(response => response.map(role => role.name))
    );
  }


}

