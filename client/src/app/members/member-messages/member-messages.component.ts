import { Component, Input, ViewChild } from '@angular/core';
import { NgForm } from '@angular/forms';
import { take } from 'rxjs';
import { Message } from 'src/app/_models/message';
import { AccountService } from 'src/app/_services/account.service';
import { MessageService } from 'src/app/_services/message.service';

@Component({
  selector: 'app-member-messages',
  templateUrl: './member-messages.component.html',
  styleUrls: ['./member-messages.component.scss']
})
export class MemberMessagesComponent {
  @ViewChild('MessageForm') messageForm?: NgForm
  @Input() username?: string;
  @Input() messages: Message[] = [];
  messageContent = '';
  photoUrl='';

  constructor(public messageService: MessageService,public accountService:AccountService) {


  }

  ngOnInit() {
    this.accountService.currentUser$.pipe(take(1)).subscribe({
      next: user => {
        if(user?.photoUrl)
        this.photoUrl = user?.photoUrl;
      }
    })
  }

  sendMessage() {
    console.log('test');
    if (!this.username) return;
    console.log('test2');
    this.messageService.sendMessage({
      "recipient_username": this.username,
      "content": this.messageContent,
      "senderPhotoUrl":this.photoUrl
    }).subscribe({
      next: (response) => {
        this.messages.unshift(response as Message);
        this.messageContent='';
      }
    })
  }


}
