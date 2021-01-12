//
//  registerOnEventViewController.swift
//  Коммуникатор
//
//  Created by Наталья Лозинская on 12.01.2021.
//

import Foundation
import UIKit

final class registerOnEventViewController: UIViewController, UIViewControllerTransitioningDelegate {

lazy var backdropView: UIView = {
    let bdView = UIView(frame: self.view.bounds)
    bdView.backgroundColor = UIColor.black.withAlphaComponent(0.5)
    return bdView
}()

let menuView = UIView()
let menuHeight = UIScreen.main.bounds.height / 3
var isPresenting = false
let count = UILabel(frame: CGRect(x: 0, y: 0, width: 200, height: 21))


init() {
    super.init(nibName: nil, bundle: nil)
    modalPresentationStyle = .custom
    transitioningDelegate = self
}

required init?(coder aDecoder: NSCoder) {
    fatalError("init(coder:) has not been implemented")
}

override func viewDidLoad() {
    super.viewDidLoad()
    
    view.backgroundColor = .clear
    view.addSubview(backdropView)
    view.addSubview(menuView)
    
    //let navbar = UINavigationBar(frame: CGRect(x: 0, y: 0, width: 200, height: 21))
    let main_label = UILabel(frame: CGRect(x: 0, y: 0, width: 100, height: 21))
    main_label.center = CGPoint(x: view.bounds.width/2, y: 21)
    main_label.textAlignment = .center
    main_label.text = "Запись"
    main_label.textColor = .black
    main_label.font = UIFont(name:"HelveticaNeue-Bold", size: 22.0)
    
    let label = UILabel(frame: CGRect(x: 0, y: 0, width: 200, height: 21))
    label.center = CGPoint(x: view.bounds.width/2, y: 80)
    label.textAlignment = .center
    label.text = "Количество гостей"
    label.textColor = .black
    label.font = UIFont(name:"HelveticaNeue-Bold", size: 17.0)
    
    let add_people = UIStepper(frame: CGRect(x: 0, y: 0, width: 200, height: 21))
    add_people.center = CGPoint(x: view.bounds.width/2, y: 145)
    add_people.minimumValue = 1
    add_people.addTarget(self, action: #selector(add_people_action), for: .touchUpInside)
    
    count.center = CGPoint(x: view.bounds.width/2, y: 114)
    count.textAlignment = .center
    count.text = "1"
    count.textColor = .black
    count.font = UIFont(name:"HelveticaNeue-Bold", size: 22.0)
    
    let button_registr = UIButton(frame: CGRect(x: 0, y: 0, width: 300, height: 50))
    button_registr.center = CGPoint(x: view.bounds.width/2, y: 204)
    button_registr.backgroundColor = UIColor(named: "Color")
    button_registr.setTitle("Записаться", for: .normal)
    button_registr.addTarget(self, action: #selector(buttonRegisterAction), for: .touchUpInside)
    button_registr.clipsToBounds = true
    button_registr.layer.cornerRadius = 14

    menuView.addSubview(main_label)
    menuView.addSubview(label)
    menuView.addSubview(count)
    menuView.addSubview(add_people)
    menuView.addSubview(button_registr)
    
    menuView.backgroundColor = .white
    menuView.translatesAutoresizingMaskIntoConstraints = false
    menuView.heightAnchor.constraint(equalToConstant: menuHeight).isActive = true
    menuView.bottomAnchor.constraint(equalTo: view.bottomAnchor).isActive = true
    menuView.leadingAnchor.constraint(equalTo: view.leadingAnchor).isActive = true
    menuView.trailingAnchor.constraint(equalTo: view.trailingAnchor).isActive = true
    
    let tapGesture = UITapGestureRecognizer(target: self, action: #selector(registerOnEventViewController.handleTap(_:)))
    backdropView.addGestureRecognizer(tapGesture)
}
    
@objc func add_people_action(_ sender: UIStepper){
    count.text = Int(sender.value).description
}
    
@objc func buttonRegisterAction(sender: UIButton!) {
  print("Button tapped")
}

@objc func handleTap(_ sender: UITapGestureRecognizer) {
    dismiss(animated: true, completion: nil)
}
}

extension registerOnEventViewController: UIViewControllerAnimatedTransitioning {
func animationController(forPresented presented: UIViewController, presenting: UIViewController, source: UIViewController) -> UIViewControllerAnimatedTransitioning? {
    return self
}

func animationController(forDismissed dismissed: UIViewController) -> UIViewControllerAnimatedTransitioning? {
    return self
}

func transitionDuration(using transitionContext: UIViewControllerContextTransitioning?) -> TimeInterval {
    return 1
}

func animateTransition(using transitionContext: UIViewControllerContextTransitioning) {
    let containerView = transitionContext.containerView
    let toViewController = transitionContext.viewController(forKey: UITransitionContextViewControllerKey.to)
    guard let toVC = toViewController else { return }
    isPresenting = !isPresenting
    
    if isPresenting == true {
        containerView.addSubview(toVC.view)
        
        menuView.frame.origin.y += menuHeight
        backdropView.alpha = 0
        
        UIView.animate(withDuration: 0.4, delay: 0, options: [.curveEaseOut], animations: {
            self.menuView.frame.origin.y -= self.menuHeight
            self.backdropView.alpha = 1
        }, completion: { (finished) in
            transitionContext.completeTransition(true)
        })
    } else {
        UIView.animate(withDuration: 0.4, delay: 0, options: [.curveEaseOut], animations: {
            self.menuView.frame.origin.y += self.menuHeight
            self.backdropView.alpha = 0
        }, completion: { (finished) in
            transitionContext.completeTransition(true)
        })
    }
}
}
