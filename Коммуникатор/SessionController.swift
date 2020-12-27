//
//  SettingsViewController.swift
//  Коммуникатор
//
//  Created by Наталья Лозинская on 05.12.2020.
//

import Foundation
import UIKit

extension UIImageView {
    func load(url: URL) {
        DispatchQueue.global().async { [weak self] in
            if let data = try? Data(contentsOf: url) {
                if let image = UIImage(data: data) {
                    DispatchQueue.main.async {
                        self?.image = image
                    }
                }
            }
        }
    }
}

class SessionController: UIViewController {
    
    
    @IBOutlet weak var activSessionView: UIView!
    
    @IBOutlet weak var balanceLabel: UILabel!
    @IBOutlet weak var qrView: UIImageView!
    @IBOutlet weak var tariffLabel: UILabel!
    
    @IBOutlet weak var timeLabel: UILabel!
    @IBOutlet weak var countLabel: UILabel!
    
    var timer = Timer()
    
    override func viewDidLoad() {
        super.viewDidLoad()
        qrView.load(url: WebFuncs.ActionUrl(action: "qrcode", params: ["code":Global.userinfo["code"] as! String]));
        timerAction()
        timer = Timer.scheduledTimer(timeInterval: 30,target: self, selector: #selector(timerAction), userInfo: nil, repeats: true) 
    }
    
    @objc func timerAction() {
       WebFuncs.JSONRequest(action: "session", params: ["sessionkey": Global.sessionkey]) { result in
            debugPrint(result);
            if let result = result {
                DispatchQueue.main.async {
                    Global.sessioninfo = result;
                    Global.balance = Global.sessioninfo["balance"] as? String ?? "0"
                    Global.is_active = Global.sessioninfo["is_active"] as? Int ?? 0
                    Global.activesession = Global.sessioninfo["active"] as? [String : AnyObject] ?? [:]
                    self.balanceLabel.text = Global.balance + " ₽"
                    self.activSessionView.isHidden = Global.is_active != 1
                    if(Global.is_active == 1) {
                        self.timeLabel.text = (Global.activesession["duration_min"] as? String ?? "0") + " мин"
                        let extra_count = (Global.activesession["count"] as? Int ?? 1) - 1
                        self.countLabel.text = extra_count == 0 ? "Я" : ("Я и еще " + (extra_count as? String ?? "1"))
                        self.tariffLabel.text = "1 мин = " + (Global.activesession["tariff_sum"] as? String ?? "1") + " ₽"
                    }
                }
            }
        }
    } 
}
