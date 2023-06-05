import { animate, state, style, transition, trigger } from '@angular/animations';
import { Component, Input } from '@angular/core';
import { Member } from '../_models/member';
import { Pagination } from '../_models/pagination';
import { UserParams } from '../_models/userParams';
import { MembersService } from '../_services/members.service';

@Component({
  selector: 'app-swipe-cards',
  templateUrl: './swipe-cards.component.html',
  styleUrls: ['./swipe-cards.component.scss'],
  animations: [
    // the fade-in/fade-out animation.
    trigger('simpleFadeAnimation', [

      // the "in" style determines the "resting" state of the element when it is visible.
      state('in', style({opacity: 1})),
      

      // fade in when created. this could also be written as transition('void => *')
      transition(':enter', [
        style({opacity: 0}),
        animate(200)
      ]),

      // fade out when destroyed. this could also be written as transition('void => *')
      transition(':leave',
        animate(400, style({opacity: 0,transform: 'translate(-400px,-100px) rotate(-15deg)'})))
    ])
  ]
})
export class SwipeCardsComponent {
   members : Member[] = [] ;
   pagination: Pagination | undefined;
   userParams: UserParams | undefined;
   animation:string='left';
   constructor(private memberService: MembersService) {
    this.userParams = this.memberService.getUserParams();
  }

  ngOnInit(): void {
    this.loadMembers();
  }

  loadMembers() {
    if (this.userParams){
      this.memberService.setUserParams(this.userParams);
      this.memberService.getMembers(this.userParams).subscribe({
        next: response => {         
          if (response.result && response.pagination) {
            this.members = response.result;
            console.log(this.members);
            this.pagination = response.pagination;          
          }
        }
      })
    }
  }

  removeMember(member:Member){
    this.members = this.members.filter(object => {
      return object.userName !== member.userName;
    });
  }

  likeUser(member:Member){
    this.removeMember(member);
  }
  dislikeUser(member:Member){
    this.removeMember(member);
  }
  
}
