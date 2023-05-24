import { Component, Input } from '@angular/core';
import { MessageThreadInfo } from '../_models/messagesThreadInfo';

@Component({
  selector: 'app-messages-thread-info',
  templateUrl: './messages-thread-info.component.html',
  styleUrls: ['./messages-thread-info.component.scss']
})
export class MessagesThreadInfoComponent {
  @Input() info: MessageThreadInfo | undefined
}
