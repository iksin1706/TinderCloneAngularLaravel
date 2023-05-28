import { ComponentFixture, TestBed } from '@angular/core/testing';

import { SwipeCardsComponent } from './swipe-cards.component';

describe('SwipeCardsComponent', () => {
  let component: SwipeCardsComponent;
  let fixture: ComponentFixture<SwipeCardsComponent>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      declarations: [ SwipeCardsComponent ]
    })
    .compileComponents();

    fixture = TestBed.createComponent(SwipeCardsComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
