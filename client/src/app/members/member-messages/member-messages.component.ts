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
    if(!this.username) return;
    this.messageService.sendMessage(this.username, this.messageContent).then(() => {
      this.messageContent='';
    })
  }


}
