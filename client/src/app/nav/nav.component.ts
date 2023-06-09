import { Component, EventEmitter, OnInit, Output } from '@angular/core';
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

  isHidden=false;
  model: any = {};
  currentUser$: Observable<User | null> = of(null);
  @Output() hideShowEvent: EventEmitter<boolean> = new EventEmitter<boolean>();


  title = 'Dating app';
  constructor(public accountService: AccountService,public messageService:MessageService , public router: Router, private toastr: ToastrService) { }

  ngOnInit(): void {
    if(this.accountService.currentUser$.pipe(take(1))) this.messageService.getMessagesThreadsInfo();
  }
  

  hide(){
    this.isHidden=true;
    this.hideShowEvent.emit(true);
  }
  show(){
    this.isHidden=false;
    this.hideShowEvent.emit(false);
  }


  logout() {
    this.accountService.logout();
    this.router.navigateByUrl('/')
  }

}
