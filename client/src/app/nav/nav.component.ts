import { Component, OnInit } from '@angular/core';
import { Router } from '@angular/router';
import { ToastrService } from 'ngx-toastr';
import { Observable, of, take } from 'rxjs';
import { MessageThreadInfo } from '../_models/messagesThreadInfo';
import { User } from '../_models/user';
import { AccountService } from '../_services/account.service';
import { MessageService } from '../_services/message.service';

@Component({
  selector: 'app-nav',
  templateUrl: './nav.component.html',
  styleUrls: ['./nav.component.scss']
})
export class NavComponent implements OnInit {

  model: any = {};
  currentUser$: Observable<User | null> = of(null);

  title = 'Dating app';
  constructor(public accountService: AccountService,public messageService:MessageService , public router: Router, private toastr: ToastrService) { }

  ngOnInit(): void {
    if(this.currentUser$.pipe(take(1))) this.messageService.getMessagesThreadsInfo();
  }


  logout() {
    this.accountService.logout();
    this.router.navigateByUrl('/')
  }

}
