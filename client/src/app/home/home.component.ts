import { HttpClient } from '@angular/common/http';
import { Component, OnInit } from '@angular/core';
import { Router } from '@angular/router';
import { Member } from '../_models/member';
import { UserParams } from '../_models/userParams';
import { MembersService } from '../_services/members.service';

@Component({
  selector: 'app-home',
  templateUrl: './home.component.html',
  styleUrls: ['./home.component.scss']
})
export class HomeComponent implements OnInit {
  registerMode = false;
  users: any;
  members: Member[] | undefined;
  userParams: UserParams | undefined;

  constructor(private http:HttpClient,public router:Router,private memberService:MembersService) { }

  ngOnInit(): void {
    this.userParams= new UserParams();
    this.userParams.pageSize=3;
    this.loadBestMembers();
  }

  registerToggle(){
    this.registerMode = !this.registerMode;
  }

  cancelRegisterMode(event: boolean){
    this.registerMode = event;
  }

  loadBestMembers(){
    if(this.userParams)
    this.memberService.getMembers(this.userParams).subscribe({
      next: response => {
        if (response.result) {
          this.members = response.result;
        }
      }
    })
  }
}
