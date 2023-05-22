import { Component, Input, ViewChild } from '@angular/core';
import { NgForm } from '@angular/forms';
import { Message } from 'src/app/_models/message';
import { MessageService } from 'src/app/_services/message.service';

@Component({
  selector: 'app-member-messages',
  templateUrl: './member-messages.component.html',
  styleUrls: ['./member-messages.component.scss']
})
export class MemberMessagesComponent {
  @ViewChild('MessageForm') messageForm?: NgForm
  @Input() username? :string;
  @Input() messages: Message[] = [];
  messageContent = '';

  constructor(public messageService: MessageService) {
    
    
  }

  ngOnInit(){

  }

  sendMessage(){
    console.log('test');
    if(!this.username) return;
    console.log('test2');
    this.messageService.sendMessage({
      "recipient_username":this.username,
      "content":this.messageContent
  }).subscribe({
      next: (response) => {
        this.messages.push(response as Message);
      }
    })
  }


}
